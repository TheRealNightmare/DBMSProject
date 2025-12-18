from datetime import datetime
from sqlalchemy.orm import Session
from pydantic import BaseModel
from models.library import ReadingSession

class SessionStart(BaseModel):
    book_id: int

def start_session(db: Session, user_id: int, data: SessionStart):
    session = ReadingSession(user_id=user_id, book_id=data.book_id, start_time=datetime.utcnow())
    db.add(session)
    db.commit()
    db.refresh(session)
    return session

def end_session(db: Session, session_id: int, pages_read: int):
    session = db.query(ReadingSession).filter(ReadingSession.id == session_id).first()
    if session:
        session.end_time = datetime.utcnow()
        session.pages_read_count = pages_read
        db.commit()
    return session