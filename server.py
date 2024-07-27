import pandas as pd
from sqlalchemy import create_engine
import streamlit as st

# Function to upload Excel files to PostgreSQL
def upload_excel_to_postgresql(file_paths, db_url):
    engine = create_engine(db_url)
    for file_path in file_paths:
        try:
            # Read Excel file into a pandas DataFrame
            df = pd.read_excel(file_path)

            # Upload DataFrame to PostgreSQL
            df.to_sql('crane_incident_db', con=engine, if_exists='append', index=False)
            st.success(f"Uploaded {file_path} to PostgreSQL successfully!")
        except Exception as e:
            st.error(f"Error uploading {file_path} to PostgreSQL: {e}")



def get_insights(question, db_url, model, prompt_template):
    engine = create_engine(db_url)
    # Use named parameters for SQL query compatibility
    query = "SELECT * FROM crane_incident_db WHERE incident_description LIKE :desc;"
    params = {'desc': f"%{question}%"}  # Using a dictionary for named parameters

    df = pd.read_sql_query(query, engine, params=params)
    context = df.to_string()

    # Use the model to generate insights
    prompt = prompt_template.render(context=context, question=question)
    answer = model.get_answer(prompt)

    return answer

