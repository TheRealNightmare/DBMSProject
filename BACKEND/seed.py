import random
from faker import Faker
from sqlalchemy.orm import Session
from database import SessionLocal, engine, Base
from controllers.account import get_password_hash

# Import your models
from models.users import User
from models.books import Book, Publisher, Author, Genre, Series, BookAuthor
from models.library import Shelf, ShelfItem
from models.ugc import Review
from models.community import Group, GroupMember

# Initialize Faker
fake = Faker()

def seed_data():
    db = SessionLocal()
    
    print("🌱 Seeding database...")

    # 1. Create Specific User (Miraz)
    # We check if he exists first to avoid duplicates if you run this twice
    existing_user = db.query(User).filter(User.email == "nnahid929@gmail.com").first()
    if not existing_user:
        miraz = User(
            username="miraz",
            email="nnahid929@gmail.com",
            password_hash=get_password_hash("1234"), # Known password
            bio="I am the admin.",
            location="Dhaka, Bangladesh",
            role="admin"
        )
        db.add(miraz)
        print("✅ Created user: Miraz")
    
    # 2. Create 20 Fake Users
    users = []
    print("... Generating 20 users")
    for _ in range(20):
        user = User(
            username=fake.unique.user_name(),
            email=fake.unique.email(),
            password_hash=get_password_hash("password"), # Default password for all
            bio=fake.sentence(),
            location=fake.city()
        )
        db.add(user)
        users.append(user)
    db.commit() # Commit to get IDs

    # 3. Create Publishers, Authors, Genres, Series
    print("... Generating Metadata (Publishers, Authors, etc.)")
    publishers = []
    for _ in range(20):
        pub = Publisher(name=fake.company(), country=fake.country())
        db.add(pub)
        publishers.append(pub)
    
    authors = []
    for _ in range(20):
        auth = Author(name=fake.name(), bio=fake.text(max_nb_chars=100))
        db.add(auth)
        authors.append(auth)

    series_list = []
    for _ in range(20):
        s = Series(title=fake.sentence(nb_words=3), description=fake.sentence())
        db.add(s)
        series_list.append(s)

    db.commit()

    # 4. Create 20 Books
    print("... Generating 20 Books")
    books = []
    for _ in range(20):
        book = Book(
            title=fake.catch_phrase(),
            isbn=fake.unique.isbn13(),
            description=fake.text(),
            page_count=random.randint(100, 1000),
            publisher_id=random.choice(publishers).id,
            language_id="en"
        )
        db.add(book)
        books.append(book)
    db.commit()

    # Link Books to Authors
    for book in books:
        # Assign 1 or 2 random authors to each book
        chosen_authors = random.sample(authors, k=random.randint(1, 2))
        for auth in chosen_authors:
            link = BookAuthor(book_id=book.id, author_id=auth.id, role="Author")
            db.add(link)
    db.commit()

    # 5. Create Shelves and Shelf Items
    print("... Filling Shelves")
    # Give 'miraz' and random users some shelves
    all_users = users + ([miraz] if not existing_user else [existing_user])
    
    for user in all_users[:10]: # Just do 10 users to save time
        shelf = Shelf(user_id=user.id, name="Favorites", privacy_level="public")
        db.add(shelf)
        db.commit()
        
        # Add 3 random books to this shelf
        for _ in range(3):
            bk = random.choice(books)
            item = ShelfItem(shelf_id=shelf.id, book_id=bk.id, status="To-Read")
            db.add(item)
    db.commit()

    # 6. Create Reviews
    print("... Writing Reviews")
    for _ in range(20):
        review = Review(
            user_id=random.choice(all_users).id,
            book_id=random.choice(books).id,
            rating=random.randint(1, 5),
            title=fake.sentence(),
            body=fake.paragraph()
        )
        db.add(review)
    db.commit()

    # 7. Create Groups
    print("... Forming Groups")
    for _ in range(5):
        grp = Group(
            name=fake.bs().title(),
            description=fake.catch_phrase(),
            created_by=random.choice(all_users).id
        )
        db.add(grp)
    db.commit()

    print("🎉 Database seeded successfully!")
    db.close()

if __name__ == "__main__":
    seed_data()