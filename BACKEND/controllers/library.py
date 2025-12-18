from sqlalchemy.orm import Session
from fastapi import HTTPException
from pydantic import BaseModel
from models.library import Shelf, ShelfItem

# --- Schemas ---
class ShelfCreate(BaseModel):
    name: str
    privacy_level: str = "public"

class ShelfItemCreate(BaseModel):
    book_id: int
    status: str = "To-Read"

# --- Logic ---
def create_shelf(db: Session, shelf: ShelfCreate, user_id: int):
    db_shelf = Shelf(**shelf.dict(), user_id=user_id)
    db.add(db_shelf)
    db.commit()
    db.refresh(db_shelf)
    return db_shelf

def add_book_to_shelf(db: Session, shelf_id: int, item: ShelfItemCreate):
    # Check if shelf exists
    shelf = db.query(Shelf).filter(Shelf.id == shelf_id).first()
    if not shelf:
        raise HTTPException(status_code=404, detail="Shelf not found")
    
    db_item = ShelfItem(shelf_id=shelf_id, book_id=item.book_id, status=item.status)
    db.add(db_item)
    db.commit()
    db.refresh(db_item)
    return db_item