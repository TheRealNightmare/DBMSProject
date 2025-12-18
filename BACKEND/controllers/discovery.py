from sqlalchemy.orm import Session
from models.books import Book
from models.users import SearchHistory

def search_books(db: Session, query: str, user_id: int = None):
    # Log history if user is logged in
    if user_id:
        history = SearchHistory(user_id=user_id, search_query=query)
        db.add(history)
        db.commit()

    # Perform search
    return db.query(Book).filter(Book.title.contains(query)).all()