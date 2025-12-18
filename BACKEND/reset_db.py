import pymysql
import os
from dotenv import load_dotenv
from database import engine, Base

# Import ALL models so Base knows about them
from models import users, books, library, ugc, community

load_dotenv()

# Database Config
DB_USER = os.getenv("DB_USER")
DB_PASSWORD = os.getenv("DB_PASSWORD")
DB_HOST = os.getenv("DB_HOST")
DB_PORT = int(os.getenv("DB_PORT", 3306))
DB_NAME = os.getenv("DB_NAME")

def reset_database():
    print("⚠️  WARNING: This will DELETE all data in 'verso_db'.")
    
    # 1. Connect directly to MySQL (no database selected yet) to drop/create
    conn = pymysql.connect(
        host=DB_HOST,
        user=DB_USER,
        password=DB_PASSWORD,
        port=DB_PORT
    )
    cursor = conn.cursor()

    try:
        # Drop and Recreate DB
        print(f"🗑️  Dropping database '{DB_NAME}'...")
        cursor.execute(f"DROP DATABASE IF EXISTS {DB_NAME}")
        
        print(f"✨ Creating database '{DB_NAME}'...")
        cursor.execute(f"CREATE DATABASE {DB_NAME}")
        conn.commit()
    finally:
        cursor.close()
        conn.close()

    # 2. Reconnect using SQLAlchemy engine to create tables
    print("🏗️  Creating tables...")
    # This looks at all imported models (users, books, community...) and builds tables
    Base.metadata.create_all(bind=engine)
    print("✅ Database reset complete! All tables created.")

if __name__ == "__main__":
    reset_database()