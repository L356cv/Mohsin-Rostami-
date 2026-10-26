##Name:Mohsin Rostami
##F/N :Safar Mohammad
##Roll/N :51


# Laravel Authentication Lab
## IT Student Portal

This project is developed using Laravel 12, PHP 8.2+, MySQL, and Blade.

## Project Objectives

- Create a user registration form.
- Validate user information.
- Store passwords securely using hashing.
- Authenticate users with email and password.
- Protect the dashboard using authentication middleware.
- Allow users to log out securely.

## 1. Routes — routes/web.php

```php
<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    Route::view('/login', 'auth.login')->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
```

## 2. Controller — AuthController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Account created. Please log in.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email or password is incorrect.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
```

## 3. Registration Form

File: `resources/views/auth/register.blade.php`

```html
<h1>Create an account</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('register.store') }}">
    @csrf

    <label for="name">Full name</label>
    <input id="name" name="name" type="text"
           value="{{ old('name') }}" required>

    <label for="email">Email</label>
    <input id="email" name="email" type="email"
           value="{{ old('email') }}" required>

    <label for="password">Password</label>
    <input id="password" name="password" type="password"
           minlength="8" required>

    <label for="password_confirmation">Confirm password</label>
    <input id="password_confirmation" name="password_confirmation"
           type="password" minlength="8" required>

    <button type="submit">Register</button>
</form>

<a href="{{ route('login') }}">Already have an account?</a>
```

## 4. Login Form

File: `resources/views/auth/login.blade.php`

```html
<h1>Login</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('login.store') }}">
    @csrf

    <label for="email">Email</label>
    <input id="email" name="email" type="email"
           value="{{ old('email') }}" required>

    <label for="password">Password</label>
    <input id="password" name="password" type="password" required>

    <button type="submit">Login</button>
</form>

<a href="{{ route('register') }}">Create an account</a>
```

## 5. Protected Dashboard

File: `resources/views/dashboard.blade.php`

```html
<h1>IT Student Portal</h1>

<p>Welcome, {{ auth()->user()->name }}.</p>
<p>Email: {{ auth()->user()->email }}</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
```

## 6. Installation and Setup

Create a Laravel 12 project:

```bash
composer create-project laravel/laravel it-auth-lab "^12.0"
cd it-auth-lab
```

Create a MySQL database named `it_auth_lab`.

Configure the database settings in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=it_auth_lab
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
```

Run the following commands:

```bash
php artisan config:clear
php artisan migrate
php artisan route:list
php artisan serve
```

Open the application at:

http://127.0.0.1:8000

## 7. Testing Checklist

- Register a new user with valid information.
- Verify that duplicate email addresses are rejected.
- Verify that passwords shorter than 8 characters are rejected.
- Verify that password confirmation must match.
- Log in using correct credentials.
- Verify that incorrect credentials show an error.
- Verify that the dashboard displays the current user's name and email.
- Verify that guests cannot access the dashboard.
- Log out and verify that the dashboard is protected.
- Test with a second user account.

## 8. Security Features

- Password hashing with `Hash::make()`.
- Input validation.
- Unique email validation.
- CSRF protection using `@csrf`.
- Authentication middleware.
- Guest middleware.
- Session regeneration after login.
- Session invalidation after logout.

## 9. Questions and Answers

### Why do we use Hash::make()?

It hashes the password before saving it to the database instead of storing the original plain-text password.

### How does auth middleware protect the dashboard?

It prevents unauthenticated visitors from accessing protected pages.

### What does @csrf do?

It adds a CSRF token to a form to help protect against cross-site request forgery attacks.

## Conclusion

This lab demonstrates the basic implementation of user authentication in Laravel 12, including registration, login, session management, protected routes, and logout.

