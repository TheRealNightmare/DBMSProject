import uvicorn
from fastapi import FastAPI
from database import engine, Base
from routes import router  # Import the router we just made

# Create tables
Base.metadata.create_all(bind=engine)

app = FastAPI(title="Verso Library API")

# Include the routes
app.include_router(router)

@app.get("/")
def read_root():
    return {"message": "Welcome to Verso API. Database Connected!"}

if __name__ == "__main__":
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)