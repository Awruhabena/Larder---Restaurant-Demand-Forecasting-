"""
runner.py
---------
Sets up the environment, runs the forecasting pipeline once, and starts
the API server.

Usage:
    python runner.py
"""

import os
import sys
import time
import subprocess
import urllib.request
import urllib.error

def print_banner(text):
    print(f"\n{'=' * 65}\n{text}\n{'=' * 65}")

def main():
    # ---------------------------------------------------------
    # STEP 1: Prepare Environment
    # ---------------------------------------------------------
    print_banner("STEP 1: PREPARING ENVIRONMENT")
    dependencies = [
        "pandas", "numpy", "statsmodels", "pmdarima", "openpyxl", 
        "google-genai", "fastapi", "uvicorn", "python-multipart"
    ]
    
    print(f"Installing/Verifying dependencies:\n{', '.join(dependencies)}\n")
    try:
        subprocess.check_call([sys.executable, "-m", "pip", "install"] + dependencies)
        print("\n✅ All dependencies installed successfully.")
    except subprocess.CalledProcessError:
        print("\n❌ Failed to install dependencies. Check your internet connection or pip setup.")
        sys.exit(1)

    # Check for Gemini API key
    if not os.environ.get("GEMINI_API_KEY"):
        print("⚠️  GEMINI_API_KEY is not set. The pipeline will use the deterministic fallback.")
    else:
        print("✅ GEMINI_API_KEY detected in environment.")

    # ---------------------------------------------------------
    # STEP 1.5: Ensure Messy Sample CSV Exists
    # ---------------------------------------------------------
    if not os.path.exists("messy_restaurant_sample.csv"):
        print("\nCreating missing 'messy_restaurant_sample.csv'...")
        sample_csv_content = """Order Dt,Dish,Qty,Ticket#
2022-01-02,spring rolls (app),2,T-5501
2022-01-02,springroll,1,T-5502
2022-01-02,SPRING ROLLS,3,T-5503
2022-01-03,chicken soup,2,T-5504
2022-01-03,chkn soup,1,T-5505
2022-01-03,garden salad,1,T-5506
"""
        with open("messy_restaurant_sample.csv", "w", encoding="utf-8") as f:
            f.write(sample_csv_content)
        print("✅ Created sample CSV.")

    # ---------------------------------------------------------
    # STEP 1.6: Verify the real patterned dataset is present
    # ---------------------------------------------------------
    # This deliberately does NOT fabricate a dataset if it's missing.
    # The real 'Restaurant_Data_Patterned.xlsx' (71 items, a full year, with
    # modelled weekend/festive demand patterns) is what every forecast and
    # buy-plan figure is computed from. A random stand-in would run without
    # erroring and silently produce meaningless results. If the real file
    # is absent, this stops and says so, rather than inventing one.
    if not os.path.exists("Restaurant_Data_Patterned.xlsx"):
        print("\n❌ Required dataset 'Restaurant_Data_Patterned.xlsx' is missing.")
        print("   Every forecast, savings figure, and buy plan is computed from")
        print("   this file. It is not auto-generated, since a fake stand-in")
        print("   would run silently and produce meaningless results.")
        print("\n   Fix: copy the real Restaurant_Data_Patterned.xlsx into this")
        print("   folder, then re-run.")
        sys.exit(1)
    else:
        # Confirms it's the real dataset, not a smaller stand-in.
        try:
            import pandas as _pd
            _items = _pd.read_excel("Restaurant_Data_Patterned.xlsx", sheet_name="Items")
            _n = len(_items)
            if _n < 50:
                print(f"\n⚠️  'Restaurant_Data_Patterned.xlsx' has only {_n} items "
                      "(expected ~71).")
                print("   This may be a stand-in rather than the real dataset —")
                print("   the computed figures will not match. Verify the file before continuing.")
            else:
                print(f"✅ Real dataset present ({_n} items).")
        except Exception as e:
            print(f"⚠️  Could not verify dataset contents: {e}")

    # ---------------------------------------------------------
    # STEP 2: Test Core Pipeline
    # ---------------------------------------------------------
    print_banner("STEP 2: RUNNING CORE PIPELINE")
    print("Executing run_pipeline.py...\n")
    
    # Run the pipeline script and wait for it to finish
    pipeline_process = subprocess.run([sys.executable, "run_pipeline.py"])
    
    if pipeline_process.returncode != 0:
        print("\n❌ Pipeline execution failed. Please check the errors above.")
        sys.exit(1)
    
    print("\n✅ Pipeline completed successfully. Excel files generated.")

    # ---------------------------------------------------------
    # STEP 3: Test and Start API Backend
    # ---------------------------------------------------------
    print_banner("STEP 3: STARTING API BACKEND")
    print("Booting Uvicorn server on port 8000...\n")
    
    # Start the API server in a separate non-blocking process
    api_process = subprocess.Popen(
        [sys.executable, "-m", "uvicorn", "api:app", "--port", "8000"]
    )

    # Give the server 3 seconds to spin up before we ping it
    print("Waiting for server to initialize...")
    time.sleep(3)

    print("\nPerforming health check on GET /headline ...")
    try:
        # Ping the API to ensure it's actually responding
        req = urllib.request.urlopen("http://localhost:8000/headline")
        if req.getcode() == 200:
            print("✅ API is UP and returning HTTP 200 OK!")
            response_data = req.read().decode('utf-8')
            print(f"Preview of response: {response_data[:100]}...")
    except urllib.error.URLError as e:
        print(f"\n❌ API Health Check Failed: {e}")
        print("Shutting down server.")
        api_process.terminate()
        sys.exit(1)

    # ---------------------------------------------------------
    # READY
    # ---------------------------------------------------------
    print_banner("SYSTEM READY")
    print("The backend is running. Connect the frontend to:")
    print("➡️  http://localhost:8000\n")
    print("To view and test the API documentation (Swagger UI), open:")
    print("➡️  http://localhost:8000/docs\n")
    print("Press Ctrl+C in this terminal to shut everything down when you are finished.")

    # Keep the script alive so the API server stays running
    try:
        api_process.wait()
    except KeyboardInterrupt:
        print("\n\nShutting down the API server...")
        api_process.terminate()
        print("Graceful shutdown complete.")

if __name__ == "__main__":
    main()