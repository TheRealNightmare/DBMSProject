from sqlalchemy import Column, Integer, String, Text, Date, ForeignKey
from sqlalchemy.orm import relationship
from models.base import BaseModel

class Publisher(BaseModel):
    __tablename__ = "publishers"
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(100), nullable=False)
    website = Column(String(255), nullable=True)
    country = Column(String(100), nullable=True)

class Genre(BaseModel):
    __tablename__ = "genres"
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(50), unique=True, nullable=False)
    slug = Column(String(50), unique=True, nullable=False)

class Author(BaseModel):
    __tablename__ = "authors"
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(100), index=True, nullable=False)
    bio = Column(Text, nullable=True)
    profile_image = Column(String(255), nullable=True)
    born_at = Column(Date, nullable=True)
    died_at = Column(Date, nullable=True)

class Series(BaseModel):
    __tablename__ = "series"
    id = Column(Integer, primary_key=True, index=True)
    title = Column(String(255), nullable=False)
    description = Column(Text, nullable=True)

class Book(BaseModel):
    __tablename__ = "books"

    id = Column(Integer, primary_key=True, index=True)
    title = Column(String(255), index=True, nullable=False)
    isbn = Column(String(20), unique=True, index=True, nullable=True)
    description = Column(Text, nullable=True)
    publication_date = Column(Date, nullable=True)
    page_count = Column(Integer, nullable=True)
    cover_image = Column(String(255), nullable=True)
    content_path = Column(String(255), nullable=True) # Path to EPUB/PDF
    
    publisher_id = Column(Integer, ForeignKey("publishers.id"), nullable=True)
    language_id = Column(String(10), nullable=True) # e.g., 'en', 'es'

    # Relationships
    publisher = relationship("Publisher")
    authors = relationship("BookAuthor", back_populates="book")
    series_links = relationship("BookSeries", back_populates="book")
    reviews = relationship("models.ugc.Review", back_populates="book")

# Association Tables
class BookAuthor(BaseModel):
    __tablename__ = "book_authors"
    book_id = Column(Integer, ForeignKey("books.id"), primary_key=True)
    author_id = Column(Integer, ForeignKey("authors.id"), primary_key=True)
    role = Column(String(50), default="Author") # Author, Illustrator, Translator

    book = relationship("Book", back_populates="authors")
    author = relationship("Author")

class BookSeries(BaseModel):
    __tablename__ = "book_series"
    book_id = Column(Integer, ForeignKey("books.id"), primary_key=True)
    series_id = Column(Integer, ForeignKey("series.id"), primary_key=True)
    series_order = Column(Integer, nullable=False) # 1, 2, 2.5

    book = relationship("Book", back_populates="series_links")
    series = relationship("Series")