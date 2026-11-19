# Idea Management System

A Laravel-based web application that allows authenticated users to create, organize, and manage their ideas, break them down into smaller steps, and track their progress.

The project was built as a hands-on learning experience to understand Laravel fundamentals, MVC architecture, database relationships, authentication, authorization, validation, file uploads, and modern frontend tools.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Database Design](#database-design)
  - [Users Table](#1-users-table)
  - [Ideas Table](#2-ideas-table)
  - [Steps Table](#3-steps-table)
  - [Database Relationships](#database-relationships)
- [Application Architecture](#application-architecture)
- [Authentication & Authorization](#authentication--authorization)
- [Routing & Middleware](#routing--middleware)
- [Validation](#validation)
- [Image Upload & Storage](#image-upload--storage)
- [Security](#security)
- [Frontend](#frontend)
  - [Blade Templates](#blade-templates)
  - [Tailwind CSS](#tailwind-css)
  - [Alpine.js](#alpinejs)
- [Testing](#testing)
- [Installation](#installation)
- [Learning Objectives](#learning-objectives)

---

## Overview

Idea Management System is a personal productivity application designed to help users manage their ideas in an organized way.

Each authenticated user can create and manage their own ideas. An idea contains a title, description, optional image, links, and a status that represents its current progress.

Users can also break down an idea into smaller actionable steps and track their completion.

The application focuses on building a clean and maintainable Laravel application while understanding how its different components work together.

---

## Features

### Idea Management
- Create new ideas.
- View ideas.
- Edit existing ideas.
- Delete ideas.
- Add descriptions to ideas.
- Assign a status to each idea.
- Attach multiple useful links.
- Upload an optional image for each idea.

### Idea Status Management

Ideas have three possible statuses:

| Status | Description |
|---|---|
| Pending | The idea has not been started yet. |
| In Progress | The idea is currently being worked on. |
| Completed | The idea has been completed. |

The statuses are represented using a PHP Enum (`IdeaStatus`), providing a consistent set of allowed values throughout the application.

### Steps Management
- Break ideas into smaller steps.
- Add multiple steps to an idea.
- Track whether a step has been completed.
- Update step information.
- Associate each step with its parent idea.

### User Management
- User registration.
- User login.
- Session-based authentication.
- User-specific idea management.

---

## Tech Stack

| Technology | Purpose |
|---|---|
| PHP | Backend programming language |
| Laravel | Backend application framework |
| MySQL | Relational database |
| Eloquent ORM | Database interactions and relationships |
| Blade | Server-side templating |
| Tailwind CSS | Utility-first CSS framework |
| Alpine.js | Lightweight frontend interactivity |
| HTML | Application structure |
| CSS | Styling |
| JavaScript | Frontend behavior |
| Pest | Application testing |

---

## Database Design

The application uses a relational database consisting of three main entities:

- Users
- Ideas
- Steps

### 1. Users Table

Stores registered users and their authentication information.

| Column | Type | Description |
|---|---|---|
| id | BIGINT | Primary key |
| name | VARCHAR | User's name |
| email | VARCHAR | Unique email address |
| password | VARCHAR | Hashed password |
| email_verified_at | TIMESTAMP | Email verification timestamp (nullable) |
| remember_token | VARCHAR | Remember-me token |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Last update date |

### 2. Ideas Table

Stores ideas created by users.

| Column | Type | Description |
|---|---|---|
| id | BIGINT | Primary key |
| user_id | BIGINT | Foreign key referencing users |
| title | VARCHAR | Idea title |
| description | TEXT | Idea description (nullable) |
| image_path | VARCHAR | Stored image path (nullable) |
| links | JSON | Collection of related links |
| status | VARCHAR | Current idea status |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Last update date |

**Important details:**
- Each idea belongs to one user.
- The `user_id` column references the `users` table.
- Deleting a user automatically deletes their associated ideas through the cascading foreign key constraint.
- The `links` column stores multiple links as JSON.
- The `status` column uses the `IdeaStatus` PHP Enum.

### 3. Steps Table

Stores individual steps associated with ideas.

| Column | Type | Description |
|---|---|---|
| id | BIGINT | Primary key |
| idea_id | BIGINT | Foreign key referencing ideas |
| description | VARCHAR | Step description |
| completed | BOOLEAN | Whether the step is completed |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Last update date |

**Important details:**
- Each step belongs to one idea.
- An idea can have multiple steps.
- Deleting an idea automatically deletes its associated steps through cascading deletion.

### Database Relationships

The application uses Laravel Eloquent relationships to connect its models.

**User → Ideas**

One user can have multiple ideas.

```php
// User.php

public function ideas()
{
    return $this->hasMany(Idea::class);
}
```

**Idea → User**

Each idea belongs to one user.

```php
// Idea.php

public function user()
{
    return $this->belongsTo(User::class);
}
```

**Idea → Steps**

One idea can contain multiple steps.

```php
// Idea.php

public function steps()
{
    return $this->hasMany(Step::class);
}
```

**Step → Idea**

Each step belongs to one idea.

```php
// Step.php

public function idea()
{
    return $this->belongsTo(Idea::class);
}
```

### Database Relationship Diagram

```text
+------------------+
|      USERS       |
+------------------+
| id               |
| name             |
| email            |
| password         |
+------------------+
         |
         | 1
         |
         | *
+------------------+
|      IDEAS       |
+------------------+
| id               |
| user_id          |
| title            |
| description      |
| image_path       |
| links            |
| status           |
+------------------+
         |
         | 1
         |
         | *
+------------------+
|      STEPS       |
+------------------+
| id               |
| idea_id          |
| description      |
| completed        |
+------------------+
```

---

## Application Architecture

The application follows Laravel's MVC (Model-View-Controller) architecture.

### Models

Models represent database entities and handle relationships and data interactions.

Main models:

- `User`
- `Idea`
- `Step`

The application also uses Eloquent features such as:

- Model relationships.
- Attribute casting.
- Mass assignment protection.
- Factories.
- Query building.

### Controllers

Controllers handle incoming HTTP requests and coordinate the application workflow.

They are responsible for receiving requests, interacting with models, and returning appropriate responses.

### Form Requests

Laravel Form Requests are used to organize and centralize request validation.

This separates validation responsibilities from the main controller logic.

### Actions

Application actions encapsulate specific business operations.

For example, the `CreateIdea` action handles the idea creation process, including creating the idea and its associated steps.

### Enums

The `IdeaStatus` enum defines the available idea statuses:

```php
enum IdeaStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
```

This prevents inconsistent status values across the application.

---

## Authentication & Authorization

### Authentication

Authentication is used to identify users and manage their login sessions.

The application uses Laravel's authentication mechanisms to:

- Register new users.
- Authenticate users using their credentials.
- Maintain authenticated sessions.
- Protect authenticated application routes.
- Redirect unauthenticated visitors to the login page.

### Authorization

Authorization determines what an authenticated user is allowed to access or modify.

The application associates ideas with their owners through the `user_id` foreign key and Eloquent relationships.

For example, creating an idea through the authenticated user's relationship:

```php
$user->ideas()->create([
    'title' => $validated['title'],
]);
```

This automatically associates the new idea with the authenticated user.

**Important:** Authentication answers "Who are you?", while authorization answers "What are you allowed to do?"

---

## Routing & Middleware

Laravel routes define how incoming HTTP requests are handled.

The application uses named routes to make URL generation and redirection easier to maintain.

Example:

```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth')->group(function () {
    // Protected application routes
});
```

### Middleware

Middleware acts as a layer between an incoming request and the application.

The application uses Laravel's built-in middleware:

| Middleware | Responsibility |
|---|---|
| `guest` | Restricts authenticated users from accessing guest-only pages. |
| `auth` | Requires users to be authenticated before accessing protected routes. |

---

## Validation

Laravel validation is used to ensure that incoming data follows the expected rules before it reaches the application logic.

The idea validation rules include:

| Field | Validation |
|---|---|
| title | Required, string, minimum 3 characters, maximum 255 characters |
| status | Required and must match the `IdeaStatus` enum |
| description | Nullable string |
| links | Nullable array |
| links.* | Must be a valid URL |
| steps | Nullable array |
| steps.* | String, maximum 255 characters |
| image | Nullable image, maximum 5120 KB |

Example:

```php
[
    'title' => [
        'required',
        'string',
        'min:3',
        'max:255',
    ],

    'status' => [
        'required',
        Rule::enum(IdeaStatus::class),
    ],

    'description' => [
        'nullable',
        'string',
    ],

    'links' => [
        'nullable',
        'array',
    ],

    'links.*' => [
        'url',
    ],

    'steps' => [
        'nullable',
        'array',
    ],

    'steps.*' => [
        'string',
        'max:255',
    ],

    'image' => [
        'nullable',
        'image',
        'max:5120',
    ],
]
```

---

## Image Upload & Storage

The application supports uploading an optional image for an idea.

Laravel's filesystem abstraction is used to handle uploaded images.

### Upload Process

1. Receive the uploaded image through an HTTP request.
2. Validate the uploaded file.
3. Store the image using Laravel's filesystem.
4. Save the generated file path in the `image_path` database column.
5. Retrieve the image when displaying the idea.

Example:

```php
$imagePath = $request->file('image')
    ->store('ideas', 'public');
```

The database stores the image path rather than storing the actual image binary data.

### Public Storage

Uploaded images are stored using Laravel's public filesystem disk.

Run the following command to create the symbolic link:

```bash
php artisan storage:link
```

Display the image in Blade:

```blade
<img
    src="{{ asset('storage/' . $idea->image_path) }}"
    alt="{{ $idea->title }}"
>
```

### Image Validation

The application validates uploaded images using Laravel's validation rules:

- The file must be an image.
- The upload is optional.
- Maximum file size is 5 MB.

---

## Security

Security is an important part of building a web application.

The project uses Laravel's built-in security features and validation mechanisms.

### CSRF Protection

Laravel protects state-changing web requests against Cross-Site Request Forgery (CSRF).

Blade forms include the CSRF token:

```blade
@csrf
```

### Password Hashing

Passwords should never be stored as plain text.

Laravel's authentication system uses password hashing to securely store user credentials.

### Input Validation

Incoming user data is validated before being processed.

This helps prevent invalid or unexpected data from entering the application.

### SQL Injection Protection

Eloquent ORM and Laravel's query builder use parameter binding when executing database queries.

This helps protect database interactions against SQL injection.

### Mass Assignment Protection

Laravel models provide mass assignment protection through `$fillable` or `$guarded`.

Only intended attributes should be allowed during mass assignment.

### Authentication Middleware

Protected routes require an authenticated session.

This prevents unauthenticated visitors from accessing restricted application functionality.

### File Upload Security

Uploaded files are validated before being stored.

The application restricts uploads to valid image files and limits their size.

---

## Frontend

The application uses Laravel Blade, Tailwind CSS, and Alpine.js to build its user interface.

### Blade Templates

Blade is Laravel's server-side templating engine.

It is used to:

- Create reusable layouts.
- Split the interface into components and partials.
- Pass data from controllers to views.
- Display dynamic database content.
- Handle conditional rendering.
- Generate forms and route URLs.

Blade components help reduce duplication and keep the interface organized.

### Tailwind CSS

Tailwind CSS is used for styling the application.

It provides utility classes that allow building responsive interfaces directly in Blade templates.

Benefits:

- Utility-first styling.
- Responsive design.
- Reusable component styles.
- Faster UI development.
- Consistent styling across the application.

### Alpine.js

Alpine.js adds lightweight interactivity without requiring a large frontend framework.

It is used for interactive UI behavior such as:

- Managing modal visibility.
- Handling component state.
- Showing and hiding elements.
- Displaying temporary success notifications.
- Applying transitions.
- Managing interactive form state.

Example of a temporary notification:

```html
<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition.opacity.duration.300ms
>
    Idea created successfully!
</div>
```

Alpine.js allows frontend interactions to be handled directly within Blade templates.

---

## Testing

The project uses Pest to write and run automated tests.

Testing focuses on verifying application behavior and ensuring that important features work as expected.

Examples of tested functionality include:

- User authentication during browser tests.
- Creating ideas.
- Associating ideas with the authenticated user.
- Creating associated steps.
- Persisting submitted idea information.
- Validating application workflows.

Run the test suite:

```bash
php artisan test
```

---

## Installation

Follow these steps to run the project locally.

### Requirements

- PHP
- Composer
- MySQL
- Node.js and npm
- Laravel-compatible PHP extensions

### 1. Clone the Repository

```bash
git clone <repository-url>

cd idea
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Create Environment File

```bash
cp .env.example .env
```

On Windows, you can also copy `.env.example` manually and rename the copy to `.env`.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Database

Update your `.env` file with your local database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=idea
DB_USERNAME=root
DB_PASSWORD=
```

Create the database before running migrations.

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 10. Start Laravel Development Server

```bash
php artisan serve
```

Open the application:

```text
http://127.0.0.1:8000
```

---

## Learning Objectives

This project was developed to gain practical experience with the following concepts:

### Laravel Fundamentals
- MVC architecture.
- Routing and named routes.
- Controllers.
- Middleware.
- Blade templates and components.
- Request lifecycle.
- Form Requests and validation.

### Database & Eloquent ORM
- Migrations.
- Foreign keys.
- Database relationships.
- One-to-many relationships.
- Eloquent queries.
- Attribute casting.
- PHP Enums.
- JSON database columns.
- Database transactions.
- Model factories.

### Authentication & Authorization
- User registration.
- Login and logout.
- Session-based authentication.
- Protected routes.
- User ownership and access control.

### File Handling
- Image validation.
- File uploads.
- Laravel filesystem.
- Public storage.
- Symbolic links.

### Frontend Development
- Tailwind CSS.
- Blade components.
- Alpine.js.
- Reactive UI behavior.
- Modal interactions.
- Notifications and transitions.

### Software Development Practices
- Separation of responsibilities.
- Reusable components.
- Clean and maintainable code.
- Automated testing with Pest.
- Understanding how Laravel features work together.

### User Profile Management
- Edit and update user profile information.
- Upload a profile picture during user registration.
- Update the profile picture from the profile editing page.
- Store uploaded profile images using Laravel's filesystem.
- Validate profile image uploads before storing them.
---

## Project Purpose

The main goal of this project is not just to build a working application, but to understand the concepts and tools behind modern Laravel development.

It provides practical experience in connecting backend logic, database design, authentication, validation, file handling, and frontend interactions into one complete application.
