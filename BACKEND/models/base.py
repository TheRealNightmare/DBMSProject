from datetime import datetime, timezone
from sqlalchemy import Column, DateTime
from database import Base as DeclarativeBase

# Helper function to get the current time in UTC (timezone-aware)
def get_utc_now():
    return datetime.now(timezone.utc)

class BaseModel(DeclarativeBase):
    __abstract__ = True
    
    # We pass the function 'get_utc_now' (without parentheses) so it runs when the record is created
    created_at = Column(DateTime, default=get_utc_now, nullable=False)
    updated_at = Column(DateTime, default=get_utc_now, onupdate=get_utc_now, nullable=False)