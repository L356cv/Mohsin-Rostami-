 
##Name:Mohsin Rostami 
##F/N:Safar Mohammad 
##department: information technology

## Objective

This project extends the existing Laravel Authentication project. Authenticated users can register books and view only the books they have added.

## Features

- User authentication
- Book registration
- Form validation
- MySQL database storage
- Bootstrap dashboard
- Personal book list
- User-specific book records
- Success and validation messages
- Protected dashboard
- Logout functionality

---

# 1. Create the Book Model and Migration

Run this command in the VS Code terminal:

```bash
php artisan make:model Book -m
```

## Migration

File: `database/migrations/xxxx_create_books_table.php`

Replace the generated migration code with:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title', 150);
            $table->string('author', 100);
            $table->unsignedInteger('pages');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
```

Run the migration:

```bash
php artisan migrate
```

---

# 2. Book Model

File: `app/Models/Book.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'pages',
        'user_id',
    ];
}
```

---

# 3. Book Controller

Create the controller:

```bash
php artisan make:controller BookController
```

File: `app/Http/Controllers/BookController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'author' => 'required|string|max:100',
            'pages' => 'required|integer|min:1|max:10000',
        ]);

        $validated['user_id'] = auth()->id();

        Book::create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Book registered successfully.');
    }
}
```

---

# 4. Routes

File: `routes/web.php`

Replace the previous dashboard route with the new `BookController` route. Do not create a duplicate dashboard route.

Keep the existing authentication routes:

```php
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    Route::view('/login', 'auth.login')
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BookController::class, 'index'])
        ->name('dashboard');

    Route::post('/books', [BookController::class, 'store'])
        ->name('books.store');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
```

---

# 5. Dashboard with Bootstrap

File: `resources/views/dashboard.blade.php`

Replace the previous dashboard content with this code:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IT Student Portal - My Books</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand">IT Student Portal</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light">
                Logout
            </button>
        </form>
    </div>
</nav>

<main class="container py-4">

    <div class="mb-4">
        <h1 class="h3">My Reading List</h1>

        <p class="text-muted">
            Welcome, {{ auth()->user()->name }}
            ({{ auth()->user()->email }})
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Book Registration Form -->

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h2 class="h5 mb-0">Register a Book</h2>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('books.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">
                        Title
                    </label>

                    <input
                        id="title"
                        name="title"
                        type="text"
                        maxlength="150"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}"
                        required
                    >

                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="author" class="form-label">
                        Author
                    </label>

                    <input
                        id="author"
                        name="author"
                        type="text"
                        maxlength="100"
                        class="form-control @error('author') is-invalid @enderror"
                        value="{{ old('author') }}"
                        required
                    >

                    @error('author')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="pages" class="form-label">
                        Pages
                    </label>

                    <input
                        id="pages"
                        name="pages"
                        type="number"
                        min="1"
                        max="10000"
                        class="form-control @error('pages') is-invalid @enderror"
                        value="{{ old('pages') }}"
                        required
                    >

                    @error('pages')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Register Book
                </button>
            </form>

        </div>
    </div>

    <!-- My Books Table -->

    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="h5 mb-0">My Books</h2>
        </div>

        <div class="card-body">

            @if ($books->isEmpty())

                <p class="text-muted mb-0">
                    You have not registered any books yet.
                </p>

            @else

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Pages</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($books as $book)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->pages }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            @endif

        </div>
    </div>

</main>

</body>
</html>
```

---

# 6. Run the Application

Make sure MySQL is running in XAMPP.

Run these commands from the Laravel project directory:

```bash
php artisan config:clear
php artisan migrate
php artisan route:list
php artisan serve
```

Open the application:

http://127.0.0.1:8000

Log in using your existing account.

---

# 7. Required Test Data

Register the following books using the dashboard form.

| Title | Author | Pages |
|---|---|---:|
| Learning PHP | Sara Ahmad | 120 |
| Laravel Basics | Ali Hassan | 180 |

Both books should appear in the My Books table.

---

# 8. Testing Checklist

- [ ] Register Learning PHP by Sara Ahmad, with 120 pages.
- [ ] Register Laravel Basics by Ali Hassan, with 180 pages.
- [ ] Verify that both books appear in the table.
- [ ] Refresh the dashboard and verify that the books remain.
- [ ] Verify that an empty title is rejected.
- [ ] Verify that an empty author is rejected.
- [ ] Verify that pages must be between 1 and 10,000.
- [ ] Verify that validation errors are displayed.
- [ ] Verify that previously entered values are restored after validation errors.
- [ ] Log in with a second account and verify that the first user's books are not displayed.
- [ ] Log out and verify that the dashboard requires authentication.
- [ ] Verify that the existing login and registration functionality still works.

---

# 9. Important Implementation Details

## Database Relationship

Each book belongs to a user through `user_id`, which references `users.id`.

## Data Validation

The application validates:

- Title: required string, maximum 150 characters.
- Author: required string, maximum 100 characters.
- Pages: required integer between 1 and 10,000.

## User Data Protection

The application assigns the owner using:

```php
$validated['user_id'] = auth()->id();
```

The application retrieves only the signed-in user's books:

```php
$books = Book::where('user_id', auth()->id())
    ->latest()
    ->get();
```

This prevents one account from viewing another account's books through the dashboard.

## CSRF Protection

The registration and logout forms include `@csrf`. The book registration form also includes `@csrf`.

## Conclusion

This project extends the Laravel Authentication Lab by adding book registration, database persistence, validation, a Bootstrap dashboard, and user-specific book viewing.
