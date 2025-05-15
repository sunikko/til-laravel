## Laravel Tech Task Demo API

Thank you for taking the time to review this task submission.

This repository contains a simple Laravel RESTful API called Laravel Tech Task Demo API, which allows you to display, add, edit, and delete tasks. It follows Laravel best practices and is built using a NoSQL database (MongoDB).

## Key criteria:

1. A task is formed of a name (min 3, max 100 characters) and a description (min 10, max 5000 characters).
2. Create an endpoint to view all tasks (no user restrictions).
3. A secured URL is required to edit or delete a task. Provide the appropriate endpoints to do this when a task is created.
4. No user accounts/ authentication is required.
5. Should be a RESTful API with a base of: `/api/tasks`.
6. Uses a NoSQL database (this is already setup).
7. Appropriate Unit Tests.
8. Uses Laravel Best Practices.

## Bonus Criteria:

1. All requests should be logged in a log file via middleware.
2. Implement Soft Deleting.

## Submission:

1. Respond to the email you received with a link to the fork of this repository with your solution in. Please include a `.env` file within your repository.
2. The three commands which will be used to run your solution will be:
    1. `composer install`
    2. `php artisan migrate`
    3. `php artisan serve`

## Implementation Notes (by Soyeon Won)

### Features Implemented

-   CRUD operations for `Task` using RESTful API.
-   `name` and `description` field validations as required (length constraints applied).
-   `secure_token` generated on task creation and required for updates and deletes to ensure secure access.
-   MongoDB used as the database backend (configured in `.env`).
-   Soft deletes implemented using `SoftDeletes`.
-   All requests are logged using middleware to `storage/logs/requests.log`.
-   Feature tests included using Laravel’s testing suite (tests/Feature/TaskApiTest.php). Run tests via `php artisan test`.

### Endpoints Summary

| Method | URI             | Description                                          |
| ------ | --------------- | ---------------------------------------------------- |
| GET    | /api/tasks      | Get all tasks                                        |
| POST   | /api/tasks      | Create a task                                        |
| GET    | /api/tasks/{id} | Get a single task                                    |
| PUT    | /api/tasks/{id} | Update task (include `secure_token` in request body) |
| DELETE | /api/tasks/{id} | Delete task (include `secure_token` in request body) |
