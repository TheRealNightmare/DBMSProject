from sqlalchemy.orm import Session
from pydantic import BaseModel
from models.ugc import Review

class ReviewCreate(BaseModel):
    book_id: int
    rating: float
    body: str

def create_review(db: Session, user_id: int, review: ReviewCreate):
    db_review = Review(**review.dict(), user_id=user_id)
    db.add(db_review)
    db.commit()
    db.refresh(db_review)
    return db_review