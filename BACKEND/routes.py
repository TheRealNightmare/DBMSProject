from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from typing import List
from fastapi.security import OAuth2PasswordRequestForm

from database import get_db
# Import controllers
from controllers import account, content, library, discovery, analytics, annotations, gamification

# Create Routers
router = APIRouter()

# --- Auth Routes ---
@router.post("/register", tags=["Auth"])
def register(user: account.UserCreate, db: Session = Depends(get_db)):
    return account.register_user(db, user)

@router.post("/token", response_model=account.Token, tags=["Auth"])
def login(login_data: account.LoginRequest, db: Session = Depends(get_db)):
    return account.login_user(db, login_data)

@router.get("/users/me", tags=["Auth"])
def read_users_me(current_user: account.User = Depends(account.get_current_user)):
    return current_user

# --- Content Routes ---
@router.get("/books/", response_model=List[content.BookOut], tags=["Books"])
def read_books(skip: int = 0, limit: int = 100, db: Session = Depends(get_db)):
    return content.get_books(db, skip=skip, limit=limit)

@router.post("/books/", response_model=content.BookOut, tags=["Books"])
def create_book(book: content.BookCreate, db: Session = Depends(get_db), current_user: account.User = Depends(account.get_current_user)):
    return content.create_book(db, book)

# --- Library Routes ---
@router.post("/shelves/", tags=["Library"])
def create_shelf(shelf: library.ShelfCreate, db: Session = Depends(get_db), current_user: account.User = Depends(account.get_current_user)):
    return library.create_shelf(db, shelf, current_user.id)

@router.post("/shelves/{shelf_id}/items", tags=["Library"])
def add_to_shelf(shelf_id: int, item: library.ShelfItemCreate, db: Session = Depends(get_db), current_user: account.User = Depends(account.get_current_user)):
    return library.add_book_to_shelf(db, shelf_id, item)

# --- Discovery Routes ---
@router.get("/search/", tags=["Discovery"])
def search(q: str, db: Session = Depends(get_db)):
    return discovery.search_books(db, q)

# --- UGC Routes ---
@router.post("/reviews/", tags=["Social"])
def add_review(review: annotations.ReviewCreate, db: Session = Depends(get_db), current_user: account.User = Depends(account.get_current_user)):
    return annotations.create_review(db, current_user.id, review)