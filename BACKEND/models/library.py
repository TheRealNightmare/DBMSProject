from sqlalchemy import Column, Integer, String, DateTime, ForeignKey, Boolean
from sqlalchemy.orm import relationship
from models.base import BaseModel

class Shelf(BaseModel):
    __tablename__ = "shelves"
    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    name = Column(String(100), nullable=False)
    is_system_default = Column(Boolean, default=False) # e.g. "Want to Read"
    privacy_level = Column(String(20), default="public") # public, private, friends

    user = relationship("models.users.User", back_populates="shelves")
    items = relationship("ShelfItem", back_populates="shelf")

class ShelfItem(BaseModel):
    __tablename__ = "shelf_items"
    id = Column(Integer, primary_key=True, index=True)
    shelf_id = Column(Integer, ForeignKey("shelves.id"), nullable=False)
    book_id = Column(Integer, ForeignKey("books.id"), nullable=False)
    status = Column(String(20), default="To-Read") # Read, Reading, DNF
    
    shelf = relationship("Shelf", back_populates="items")
    book = relationship("models.books.Book")

class ReadingSession(BaseModel):
    __tablename__ = "reading_sessions"
    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    book_id = Column(Integer, ForeignKey("books.id"), nullable=False)
    start_time = Column(DateTime, nullable=False)
    end_time = Column(DateTime, nullable=True)
    pages_read_count = Column(Integer, default=0)

class ReadingGoal(BaseModel):
    __tablename__ = "reading_goals"
    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    year = Column(Integer, nullable=False)
    target_amount = Column(Integer, nullable=False)