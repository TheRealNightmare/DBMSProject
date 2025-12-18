from sqlalchemy import Column, Integer, Boolean, String, Text, ForeignKey, Float
from sqlalchemy.orm import relationship, backref
from models.base import BaseModel

# --- Reviews ---
class Review(BaseModel):
    __tablename__ = "reviews"
    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    book_id = Column(Integer, ForeignKey("books.id"), nullable=False)
    rating = Column(Float, nullable=False) # 1.0 to 5.0
    title = Column(String(255), nullable=True)
    body = Column(Text, nullable=True)
    contains_spoilers = Column(Boolean, default=False)

    user = relationship("models.users.User")
    book = relationship("models.books.Book", back_populates="reviews")
    comments = relationship("ReviewComment", back_populates="review")
    votes = relationship("ReviewVote", back_populates="review")

class ReviewComment(BaseModel):
    __tablename__ = "review_comments"
    id = Column(Integer, primary_key=True, index=True)
    review_id = Column(Integer, ForeignKey("reviews.id"), nullable=False)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    body = Column(Text, nullable=False)
    
    # Threading logic
    parent_comment_id = Column(Integer, ForeignKey("review_comments.id"), nullable=True)
    replies = relationship("ReviewComment", back_populates="parent", remote_side=[id])
    parent = relationship("ReviewComment", back_populates="replies", remote_side=[parent_comment_id])
    
    review = relationship("Review", back_populates="comments")

class ReviewVote(BaseModel):
    __tablename__ = "review_votes"
    id = Column(Integer, primary_key=True, index=True)
    review_id = Column(Integer, ForeignKey("reviews.id"), nullable=False)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    vote_type = Column(String(10)) # 'up', 'down'

    review = relationship("Review", back_populates="votes")

# --- Annotations ---
class AnnotationLayer(BaseModel):
    __tablename__ = "annotation_layers"
    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False) # Creator
    book_id = Column(Integer, ForeignKey("books.id"), nullable=False)
    title = Column(String(100), nullable=False)
    description = Column(Text, nullable=True)
    price = Column(Float, default=0.0) # 0.0 = free

    annotations = relationship("Annotation", back_populates="layer")

class Annotation(BaseModel):
    __tablename__ = "annotations"
    id = Column(Integer, primary_key=True, index=True)
    layer_id = Column(Integer, ForeignKey("annotation_layers.id"), nullable=False)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    start_offset = Column(Integer, nullable=False) # Character/Word index
    end_offset = Column(Integer, nullable=False)
    color_code = Column(String(7), default="#FFFF00") # Hex
    note_text = Column(Text, nullable=True)

    layer = relationship("AnnotationLayer", back_populates="annotations")

class LayerSubscription(BaseModel):
    __tablename__ = "layer_subscriptions"
    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False) # Subscriber
    layer_id = Column(Integer, ForeignKey("annotation_layers.id"), nullable=False)