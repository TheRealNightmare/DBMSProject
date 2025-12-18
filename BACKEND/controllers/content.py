from sqlalchemy.orm import Session
from fastapi import HTTPException
from pydantic import BaseModel
from typing import List, Optional
from models.books import Book, Author

# --- Schemas ---
class BookBase(BaseModel):
    title: str
    isbn: Optional[str] = None
    description: Optional[str] = None
    page_count: Optional[int] = None

class BookCreate(BookBase):
    pass

class BookOut(BookBase):
    id: int
    class Config:
        from_attributes = True

# --- Logic ---
def get_books(db: Session, skip: int = 0, limit: int = 100):
    return db.query(Book).offset(skip).limit(limit).all()

def create_book(db: Session, book: BookCreate):
    db_book = Book(**book.dict())
    db.add(db_book)
    db.commit()
    db.refresh(db_book)
    return db_book

def get_book_by_id(db: Session, book_id: int):
    return db.query(Book).filter(Book.id == book_id).first()