Name: Mohsin Rostami - Student ID:p01031883

===== Task 1 - PHP and Composer =====
PHP 8.2.12 (cli) (built: Oct 24 2023 21:15:15) (ZTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.2.12, Copyright (c) Zend Technologies
Composer version 2.10.3 2026-08-27 13:34:23

===== Task 2 - Laravel project =====
Laravel Framework 12.69.2
.claude
.editorconfig
.env
.env.example
.gitattributes
.gitignore
.mcp.json
AGENTS.md
app
artisan
boost.json
bootstrap
composer.json
composer.lock
config
database
node_modules
package-lock.json
package.json
phpunit.xml
public
README.md
resources
routes
storage
tests
vendor
vite.config.js

===== Task 3 - resources/views/home.blade.php =====
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My First Laravel Page</title>
</head>
<body>
    <h1>Welcome to My Laravel Website</h1>
    <p>Student: Mohsin Rostami</p>
    <p>Student ID: p01031883</p>
    <p>Course: {{ $course }}</p>
    <p>This is my first Blade view.</p>

    <a href="{{ url('/about') }}">About Me</a>
</body>
</html>

===== Task 4 - routes/web.php (includes the /about route from Task 5) =====
<?php

use Illuminate\Support\Facades\Route;

// Home page route: shows the home view and sends the course name to it
Route::get('/', function () {
    return view('home', [
        'course' => 'Web Information Systems'
    ]);
});

// About page route: shows the about view
Route::get('/about', function () {
    return view('about');
});

===== Task 5 - resources/views/about.blade.php =====
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Me</title>
</head>
<body>
    <h1>About Me</h1>
    <p>Full Name: Mohsin Rostami</p>
    <p>Student ID: p01031883</p>
    <p>I want to learn how Laravel routes and views work together. I also want to build a small web application with it.</p>

    <a href="{{ url('/') }}">Back to Home</a>
</body>
</html>

===== Quick understanding questions =====

1. In which folder do you place Blade view files?
Answer: Blade view files are placed in the resources/views folder.

2. Which file contains the routes used in this lab?
Answer: The routes are in the routes/web.php file.

3. What does return view('home') do?
Answer: It loads resources/views/home.blade.php and returns the rendered HTML page to the browser, with any data passed to it.

4. What is the difference between the URL /about and the file about.blade.php?
Answer: /about is the URL the user types in the browser and it is handled by a route. about.blade.php is the actual file that holds the page content. The route connects the URL to the view file.
