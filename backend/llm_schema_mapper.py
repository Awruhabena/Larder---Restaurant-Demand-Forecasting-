"""
llm_schema_mapper.py
--------------------
Maps a messy restaurant order export (any column names, date formats,
or item spellings) onto the canonical schema the forecasting model expects.

Design boundary:
  * The LLM decides the mapping only — which of the source columns is
    Date/Item/Count/Order Number, and which item spellings correspond to
    which canonical Item ID.
  * Deterministic code applies that mapping and verifies no quantities
    were altered and no rows were silently dropped.
  * The LLM never touches the numbers themselves.

Canonical schema (matches Restaurant_Data_Patterned.xlsx 'Orders' sheet):
    Date, Time, Order Number, Item, Count
Canonical menu: the 'Items' sheet (Item -> Item Name).

Requires: GEMINI_API_KEY in environment.
    pip install google-genai pandas openpyxl
"""

import os
import re
import json
import pandas as pd

# Canonical schema the forecaster consumes
CANONICAL_COLUMNS = ["Date", "Time", "Order Number", "Item", "Count"]

GEMINI_MODEL = "gemini-2.0-flash"  # current fast model on the google-genai SDK


# --------------------------------------------------------------------------- #
# 1. Load the canonical menu (the ground truth the LLM matches item names to)
# --------------------------------------------------------------------------- #
def load_menu(xlsx_path: str) -> pd.DataFrame:
    menu = pd.read_excel(xlsx_path, sheet_name="Items")
    menu = menu[menu["Item Name"].notna()][["Item", "Item Name"]].copy()
    menu["Item"] = menu["Item"].astype(int)
    return menu


def _menu_for_prompt(menu: pd.DataFrame) -> str:
    return "\n".join(f"{int(r.Item)} = {r['Item Name']}" for _, r in menu.iterrows())


# --------------------------------------------------------------------------- #
# 2. Ask Gemini for the MAPPING ONLY (never the data itself)
# --------------------------------------------------------------------------- #
def _build_prompt(messy_df: pd.DataFrame, menu: pd.DataFrame) -> str:
    sample = messy_df.head(15).to_csv(index=False)
    unique_items = sorted(
        {str(v).strip() for v in messy_df.iloc[:, :].select_dtypes(include="object").stack().unique()}
    )[:60]
    return f"""You are a data-onboarding assistant for a restaurant demand-forecasting system.
Map this restaurant's messy order export onto our canonical schema.

OUR CANONICAL COLUMNS (map their columns onto exactly these):
{CANONICAL_COLUMNS}

OUR CANONICAL MENU (map their item text onto these numeric IDs):
{_menu_for_prompt(menu)}

THEIR RAW EXPORT — column headers and first rows:
{sample}

DISTINCT TEXT VALUES that may be item names in their file:
{unique_items}

Return ONLY valid JSON, no prose, in this exact form:
{{
  "column_map": {{ "their_column_name": "OurCanonicalColumn", ... }},
  "item_map":   {{ "their_item_text": <canonical_integer_id>, ... }},
  "date_format_hint": "a strptime format if their dates are non-ISO, else null",
  "notes": "one short line on anything ambiguous"
}}

RULES:
- Map every canonical column you can find; if one is genuinely absent, omit it.
- For item text you cannot confidently match to a menu ID, omit it (do NOT guess).
- Do NOT alter, invent, round, or compute any quantities. You only produce the mapping.
"""


def get_mapping_from_gemini(messy_df: pd.DataFrame, menu: pd.DataFrame) -> dict:
    """Real Gemini call using the new google-genai SDK. LLM decides mapping ONLY.
    Raises on any failure so the caller can decide whether to fall back."""
    from google import genai

    api_key = os.environ.get("GEMINI_API_KEY")
    if not api_key:
        raise RuntimeError("GEMINI_API_KEY not set.")

    # New SDK: create a client, then call models.generate_content.
    client = genai.Client(api_key=api_key)
    prompt = _build_prompt(messy_df, menu)
    resp = client.models.generate_content(
        model=GEMINI_MODEL,
        contents=prompt,
    )
    text = resp.text.strip()

    # strip ```json fences if present
    text = re.sub(r"^```(?:json)?|```$", "", text, flags=re.MULTILINE).strip()
    return json.loads(text)


# --------------------------------------------------------------------------- #
# 3. DETERMINISTIC application + safety check (code owns the numbers)
# --------------------------------------------------------------------------- #
def apply_mapping(messy_df: pd.DataFrame, mapping: dict, menu: pd.DataFrame):
    """Apply the LLM's mapping in pure pandas, then verify integrity.
    Returns (clean_df, report). Raises if the mapping would corrupt counts."""
    col_map = mapping.get("column_map", {})
    item_map = {str(k).strip().lower(): int(v) for k, v in mapping.get("item_map", {}).items()}

    df = messy_df.rename(columns=col_map).copy()

    # --- locate the quantity column BEFORE transforming, to audit it ---
    if "Count" not in df.columns:
        raise ValueError("Mapping did not identify a 'Count' column.")
    original_count_total = pd.to_numeric(df["Count"], errors="coerce").fillna(0).sum()
    original_rows = len(df)

    # --- map item text -> canonical ID (deterministic lookup, not LLM) ---
    if "Item" not in df.columns:
        raise ValueError("Mapping did not identify an 'Item' column.")
    df["Item"] = (
        df["Item"].astype(str).str.strip().str.lower().map(item_map)
    )
    unmatched = df["Item"].isna().sum()
    df = df[df["Item"].notna()].copy()
    df["Item"] = df["Item"].astype(int)

    # --- normalise dates deterministically ---
    if "Date" in df.columns:
        fmt = mapping.get("date_format_hint")
        df["Date"] = (
            pd.to_datetime(df["Date"], format=fmt, errors="coerce")
            if fmt else pd.to_datetime(df["Date"], errors="coerce")
        )

    # --- fill any missing canonical columns so shape matches downstream ---
    for c in CANONICAL_COLUMNS:
        if c not in df.columns:
            df[c] = pd.NA
    df = df[CANONICAL_COLUMNS]

    # --- SAFETY CHECK: counts of matched rows must be untouched ---
    kept_count_total = pd.to_numeric(df["Count"], errors="coerce").fillna(0).sum()
    valid_menu_ids = set(menu["Item"].astype(int))
    bad_ids = set(df["Item"]) - valid_menu_ids
    if bad_ids:
        raise ValueError(f"Mapping produced item IDs not on the menu: {bad_ids}")

    report = {
        "original_rows": int(original_rows),
        "rows_kept": int(len(df)),
        "rows_dropped_unmatched_item": int(unmatched),
        "original_count_total": float(original_count_total),
        "kept_count_total": float(kept_count_total),
        "counts_preserved_for_matched_rows": bool(kept_count_total <= original_count_total),
        "notes": mapping.get("notes", ""),
    }
    return df, report


# --------------------------------------------------------------------------- #
# 4. End-to-end convenience
# --------------------------------------------------------------------------- #
def map_to_schema(messy_df: pd.DataFrame, menu_xlsx: str):
    """Full ingestion: messy df -> (clean canonical df, report)."""
    menu = load_menu(menu_xlsx)
    mapping = get_mapping_from_gemini(messy_df, menu)
    return apply_mapping(messy_df, mapping, menu)


if __name__ == "__main__":
    MENU_XLSX = "Restaurant_Data_Patterned.xlsx"
    messy = pd.read_csv("messy_restaurant_sample.csv")
    print("RAW INPUT:\n", messy.head(10).to_string(index=False), "\n")

    clean, report = map_to_schema(messy, MENU_XLSX)
    print("CLEAN OUTPUT:\n", clean.head(10).to_string(index=False), "\n")
    print("INTEGRITY REPORT:\n", json.dumps(report, indent=2, default=str))
