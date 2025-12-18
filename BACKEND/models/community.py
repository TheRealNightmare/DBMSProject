from sqlalchemy import Column, Integer, String, Text, ForeignKey, DateTime
from sqlalchemy.orm import relationship
from models.base import BaseModel

# --- Groups ---
class Group(BaseModel):
    __tablename__ = "groups"
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(100), nullable=False)
    description = Column(Text, nullable=True)
    created_by = Column(Integer, ForeignKey("users.id"))

class GroupMember(BaseModel):
    __tablename__ = "group_members"
    group_id = Column(Integer, ForeignKey("groups.id"), primary_key=True)
    user_id = Column(Integer, ForeignKey("users.id"), primary_key=True)
    role = Column(String(20), default="member") # admin, moderator, member

class GroupMessage(BaseModel):
    __tablename__ = "group_messages"
    id = Column(Integer, primary_key=True, index=True)
    group_id = Column(Integer, ForeignKey("groups.id"), nullable=False)
    sender_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    body = Column(Text, nullable=False)

# --- Events ---
class Event(BaseModel):
    __tablename__ = "events"
    id = Column(Integer, primary_key=True, index=True)
    title = Column(String(255), nullable=False)
    host_type = Column(String(50)) # User, Group, Publisher
    host_id = Column(Integer, nullable=False) # ID of the entity above
    start_time = Column(DateTime, nullable=False)
    end_time = Column(DateTime, nullable=True)

class EventAttendee(BaseModel):
    __tablename__ = "event_attendees"
    event_id = Column(Integer, ForeignKey("events.id"), primary_key=True)
    user_id = Column(Integer, ForeignKey("users.id"), primary_key=True)
    status = Column(String(20), default="Going") # Going, Maybe

# --- Exchange ---
class ExchangeListing(BaseModel):
    __tablename__ = "exchange_listings"
    id = Column(Integer, primary_key=True, index=True)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    # Links to a specific book the user owns
    shelf_item_id = Column(Integer, ForeignKey("shelf_items.id"), nullable=False)
    condition = Column(String(50)) # New, Used-Good, Used-Fair
    exchange_type = Column(String(50)) # Sell, Swap, Gift
    status = Column(String(20), default="active") # active, pending, closed

    requests = relationship("ExchangeRequest", back_populates="listing")

class ExchangeRequest(BaseModel):
    __tablename__ = "exchange_requests"
    id = Column(Integer, primary_key=True, index=True)
    listing_id = Column(Integer, ForeignKey("exchange_listings.id"), nullable=False)
    requester_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    status = Column(String(20), default="pending") # pending, accepted, rejected

    listing = relationship("ExchangeListing", back_populates="requests")