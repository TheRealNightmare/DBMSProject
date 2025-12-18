import uvicorn  # Import uvicorn
from fastapi import FastAPI
from database import engine, Base
from models import users, books, library, ugc, community

# Create the database tables
Base.metadata.create_all(bind=engine)

app = FastAPI(title="Verso Library API")

@app.get("/")
def read_root():
    return {"message": "Welcome to Verso API. Database Connected!"}

# Add this block to run the server when executing 'python main.py'
if __name__ == "__main__":
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)