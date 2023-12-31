﻿# Phantom Flow Framework

## Introduction

Hello there! Welcome to **Phantom Flow**, a lightweight and free-flowing development framework designed for users who don't have access to popular frameworks like Laravel or CodeIgniter. If you're developing projects directly from cPanel, hPanel, or working with subdirectories, Phantom Flow is here to make PHP backend development easier and more organized.

## Features

- Follows the MVC (Model-View-Controller) convention for structured development.
- Provides simple routing to map URLs to controllers and actions.
- Includes views for rendering your HTML templates.
- Enables you to build your PHP backend projects with ease.

## Getting Started

1. Clone or download this repository to your local machine.
2. Set up your web server (Apache, Nginx, etc.) to point to the project directory.
3. Start building your PHP backend using the Phantom Flow framework.

## Directory Structure

- `app`: Contains the core application files.
    - `controllers`: Place your controller files here.
    - `models`: Store your model classes here.
    - `views`: Create view templates using PHP and HTML.
- `core`: Store configuration files.
    - `config`: Store configuration files, including database connections and common query builders.
    - `Request.php`: Handles HTTP request parsing and handling.
    - `Router.php`: Implements the routing system for mapping URLs to controllers and actions.
    - `Bootstrap.php`: Initializes and configures the framework.
- `vendor`: Dependencies and third-party libraries.

## Usage

1. Define your routes in the `routes` directory.
2. Create controller files in the `app/controllers` directory.
3. Place your model classes in the `app/models` directory.
4. Design your view templates in the `app/views` directory.
5. Use the simple routing to map URLs to controllers and actions.

## Example

```php
// Define a route in app/routes.php
$router->get('/', 'HomeController@index');

// Create a controller in app/controllers/HomeController.php
class HomeController {
    public function index() {
        // Load a view template
        return view('home');
    }
}
```

## Author

Aniruddh Vishwakarma.
-PHP Laravel Backend Developer

