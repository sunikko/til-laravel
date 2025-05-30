## Laravel + MongoDB Demo API

This project is a practice implementation of a simple RESTful API built with Laravel and MongoDB, which allows you to display, add, edit, and delete tasks. It follows Laravel best practices and is built using a NoSQL database (MongoDB).
It was originally based on a technical task, but has since been adapted into a personal learning project.

**Key criteria:**

1. A task is formed of a name (min 3, max 100 characters) and a description (min 10, max 5000 characters).
2. Create an endpoint to view all tasks (no user restrictions).
3. A secured URL is required to edit or delete a task. Provide the appropriate endpoints to do this when a task is created.
4. No user accounts/ authentication is required.
5. Should be a RESTful API with a base of: `/api/tasks`.
6. Uses a NoSQL database (this is already setup).
7. Appropriate Unit Tests.
8. Uses Laravel Best Practices.

**Bonus Criteria:**

1. All requests should be logged in a log file via middleware.
2. Implement Soft Deleting.

**Submission:**

1. Respond to the email you received with a link to the fork of this repository with your solution in. Please include a `.env` file within your repository.
2. The three commands which will be used to run your solution will be:
    1. `composer install`
    2. `php artisan migrate`
    3. `php artisan serve`

## Implementation Notes (by Soy)

### Features Implemented

-   CRUD operations for `Task` using RESTful API.
-   `name` and `description` field validations as required (length constraints applied).
-   `secure_token` generated on task creation and required for updates and deletes to ensure secure access.
-   MongoDB used as the database backend (configured in `.env`).
-   Soft deletes implemented using `SoftDeletes`.
-   All requests are logged using middleware to `storage/logs/requests.log`.
-   Feature tests included using Laravel’s testing suite. Run tests via `php artisan test`.
-   This project includes unit tests for the `TaskController` to ensure its core methods behave as expected in isolation using mocked dependencies. Run tests via `php artisan test --filter TaskControllerUnitTest`

### Endpoints Summary

| Method | URI             | Description                                          |
| ------ | --------------- | ---------------------------------------------------- |
| GET    | /api/tasks      | Get all tasks                                        |
| POST   | /api/tasks      | Create a task                                        |
| GET    | /api/tasks/{id} | Get a single task                                    |
| PUT    | /api/tasks/{id} | Update task (include `secure_token` in request body) |
| DELETE | /api/tasks/{id} | Delete task (include `secure_token` in request body) |

## Reflections

Though I’m relatively new to Laravel, my prior experience with modern web frameworks like Django made the transition smooth. As a PHP developer, I found Laravel to be intuitive and developer-friendly.

Laravel offered a modern and structured development experience, reminiscent of Django but in the PHP ecosystem. This project gave me the opportunity to explore Laravel’s core features and its ecosystem, especially in combination with a NoSQL backend.

I'm planning to continue developing with Laravel and consider using it in real-world applications.

### 🎯 TIL

-   Understand the basic structure and routing of [Laravel 12.x](https://laravel.com/docs/12.x)
-   Set up MongoDB with Laravel using laravel-mongodb package [Laravel-mongodb](https://www.mongodb.com/ko-kr/docs/drivers/php/laravel-mongodb/current/quick-start/view-data/)
-   Test basic CRUD operations with MongoDB inside a Laravel controller

### 📚 What I Learned

-   Laravel shares similarities with Django in its MVC structure and clear folder organization, which made it feel familiar
-   The php artisan command-line tool makes it quick and efficient to generate migrations, models, and controllers
-   I installed the laravel-mongodb package and successfully connected it to MongoDB
-   Tested basic CRUD operations: storing documents, fetching data, and applying filters using Eloquent-style queries
-   Laravel 12 uses a new bootstrap process; bootstrap/app.php is more streamlined
-   laravel-mongodb package provides an easy way to use MongoDB as an Eloquent-compatible database
-   Collections in MongoDB behave differently compared to MySQL tables—schema-less structure gives more flexibility but requires clear data handling in code

### 💡 Reflections

-   Although I'm new to Laravel, my experience with modern frameworks like Django helped me adapt quickly
-   As a PHP developer, it felt surprisingly comfortable—Laravel fits naturally with the language I'm familiar with
-   While integrating MongoDB was interesting, what impressed me most was Laravel itself
-   I had the feeling that this framework is strong enough to build real, solid projects—it gave me confidence to consider developing with it seriously
-   The MongoDB integration felt smoother than expected thanks to the laravel-mongodb package

### 🛠 Tomorrow's Plan

-   Try out Laravel’s authentication features
-   Practice modeling nested documents and relationships in MongoDB
-   TDD

### 🧠 Free thoughts

-   I’m getting more comfortable with Laravel’s structure
-   Laravel feels like “the Django of PHP” with its structured and modern approach
-   It’s reassuring to see that PHP can still be used for clean, modern development
-   I'm starting to consider whether I can bring Laravel into future real-world projects
