import pandas as pd
from sqlalchemy import create_engine

# List of file paths
file_paths = [
    "C:\\Users\\Windows\\Downloads\\College.xlsx",
]

# Updated database URL
db_url = 'postgresql+psycopg2://postgres:ZHOUwenBOda3@localhost:5432/'
engine = create_engine(db_url)

def upload_to_database(file_path, engine):
    # Determine file extension and read the file accordingly
    if file_path.endswith('.xlsm'):
        df = pd.read_excel(file_path, engine='openpyxl')  # Use openpyxl engine for xlsm
    elif file_path.endswith('.xlsx'):
        df = pd.read_excel(file_path, engine='openpyxl')  # Use openpyxl engine for xlsx
    else:
        raise ValueError(f"Unsupported file type: {file_path}")

    # Extract table name from file path (customize as needed)
    table_name = file_path.split("\\")[-1].split('.')[0]

    # Upload the DataFrame to PostgreSQL
    df.to_sql(table_name, engine, if_exists='replace', index=False)

# Loop through the file paths and upload each to the database
for file_path in file_paths:
    upload_to_database(file_path, engine)