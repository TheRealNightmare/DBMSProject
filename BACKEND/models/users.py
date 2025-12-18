from sqlalchemy import Column, Integer, String, Text, ForeignKey, DateTime
from sqlalchemy.orm import relationship
from models.base import BaseModel

class User(BaseModel):
    __tablename__ = "users"

    id = Column(Integer, primary_key=True, index=True)
    username = Column(String(50), unique=True, index=True, nullable=False)
    email = Column(String(100), unique=True, index=True, nullable=False)
    password_hash = Column(String(255), nullable=False)
    profile_image = Column(String(255), nullable=True)
    bio = Column(Text, nullable=True)
    location = Column(String(100), nullable=True)

    # Relationships
    notifications = relationship("Notification", back_populates="user")
    shelves = relationship("models.library.Shelf", back_populates="user")
    
    # Self-referential Follows relationship
    # We define the relationship to the 'Follow' model defined below
    followers = relationship(
        "Follow",
        foreign_keys="Follow.followed_id",
        back_populates="followed"
    )
    following = relationship(
        "Follow",
        foreign_keys="Follow.follower_id",
        back_populates="follower"
    )

class Follow(BaseModel):
    __tablename__ = "follows"

    follower_id = Column(Integer, ForeignKey("users.id"), primary_key=True)
    followed_id = Column(Integer, ForeignKey("users.id"), primary_key=True)

    follower = relationship("User", foreign_keys=[follower_id], back_populates="following")
    followed = relationship("User", foreign_keys=[followed_id], back_populates="followers")

class Notification(BaseModel):
    __tablename__ = "notifications"

    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    type = Column(String(50), nullable=False) # e.g., 'new_follower', 'book_release'
    data = Column(Text, nullable=True) # JSON string
    read_at = Column(DateTime, nullable=True)

    user = relationship("User", back_populates="notifications")