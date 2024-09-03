Library Management System
Overview
The Library Management System is a comprehensive web application built using Laravel for managing books, users, and borrow records in a library. This system allows users to add, update, and delete books, manage user accounts, track borrowing and returning of books, and handle ratings and categories. The system also includes authentication with JWT, providing secure access to the application.

Features
Books Management: Add, update, view, and delete books.
Users Management: Add, update, view, and delete user accounts.
Borrow Records: Track borrowing and returning of books, with automatic due date calculations.
Ratings: Users can rate books they have borrowed.
Categories: Manage book categories and ensure data integrity with associated books.
Authentication: Secure login and registration using JWT for API access.
Authorization: Ensure users can only perform actions they are authorized for (e.g., users can only update their own ratings).
Setup
Prerequisites
PHP 8.0 or higher
Composer
Laravel 10.x
MySQL or any other compatible database
Installation
Clone the Repository


git clone https://github.com/yourusername/library-management-system.git
cd library-management-system
Install Dependencies


composer install
Set Up Environment File

Copy the example environment file and update the database configuration:


cp .env.example .env
Edit the .env file to configure your database and other environment settings.

Generate Application Key


php artisan key:generate
Run Migrations

Create the necessary tables in your database:


php artisan migrate
Seed Database (Optional)

Seed the database with sample data:


php artisan db:seed
Serve the Application

Start the Laravel development server:


php artisan serve
The application will be available at http://localhost:8000.

API Endpoints
Authentication
POST /api/register: Register a new user.
POST /api/login: Authenticate and get a JWT token.
Books
GET /api/books: List all books with optional filters.
POST /api/books: Add a new book (admin only).
PUT /api/books/{id}: Update a book (admin only).
DELETE /api/books/{id}: Delete a book (admin only).
Users
GET /api/users: List all users (admin only).
POST /api/users: Add a new user (admin only).
PUT /api/users/{id}: Update user details (admin only).
DELETE /api/users/{id}: Delete a user (admin only).
Borrow Records
GET /api/borrows: List all borrow records.
POST /api/borrows: Create a new borrow record.
PUT /api/borrows/{id}: Update a borrow record (e.g., return a book).
DELETE /api/borrows/{id}: Delete a borrow record.
Ratings
POST /api/ratings: Add a rating for a book.
PUT /api/ratings/{id}: Update a rating (must be the user's own rating).
DELETE /api/ratings/{id}: Delete a rating (must be the user's own rating).
Categories
GET /api/categories: List all categories.
POST /api/categories: Add a new category (admin only).
PUT /api/categories/{id}: Update a category (admin only).
DELETE /api/categories/{id}: Delete a category (admin only).
Validation and Error Handling
Validation: The system uses Laravel's Form Request validation for input validation. Custom validation rules and error messages are provided.
Error Handling: Custom error responses are provided for various validation errors and authorization issues.

Testing
Run tests using PHPUnit:
php artisan test
test the project with postman:https://documenter.getpostman.com/view/34383133/2sAXjNXWXS

Contributing
Contributions are welcome! Please submit pull requests and ensure that tests are included with your changes.

License
This project is licensed under the MIT License.

Acknowledgments
Laravel for the powerful framework.
JWT for secure authentication.
All contributors who have helped build and improve this system.
