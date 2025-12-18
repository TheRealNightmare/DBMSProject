from sqlalchemy.orm import Session
from pydantic import BaseModel
from models.library import ReadingGoal

class GoalCreate(BaseModel):
    year: int
    target_amount: int

def set_reading_goal(db: Session, user_id: int, goal: GoalCreate):
    db_goal = ReadingGoal(**goal.dict(), user_id=user_id)
    db.add(db_goal)
    db.commit()
    db.refresh(db_goal)
    return db_goal