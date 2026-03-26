<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 1.5rem;
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4b5563;
            font-weight: 500;
            font-size: 0.9rem;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            color: #1f2937;
            transition: border-color 0.2s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        button {
            width: 100%;
            padding: 0.875rem;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 1rem;
        }
        button:hover {
            background-color: #2563eb;
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #6b7280;
        }
        .login-link a {
            color: #3b82f6;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    @auth
    <div class="container">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p style="text-align: center; color: #4b5563; margin-bottom: 1.5rem;">You are successfully logged in.</p>
        <form action="/logout" method="POST" style="margin-bottom: 2rem;">
            @csrf
            <button type="submit" style="background-color: #ef4444;">Log Out</button>
        </form>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin-bottom: 1.5rem;">

        <h3 style="color: #1f2937; margin-top: 0;">My To-Do List</h3>
        
        <!-- Add To-Do Form -->
        <form action="/todos" method="POST" style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem;">
            @csrf
            <input type="text" name="title" placeholder="New task..." required style="flex-grow: 1; margin: 0; padding: 0.75rem;">
            <button type="submit" style="width: auto; margin: 0; padding: 0.75rem 1rem;">Add</button>
        </form>

        <!-- To-Do Items -->
        @if(isset($todos) && $todos->count() > 0)
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach($todos as $todo)
                    <li style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 0.5rem;">
                        <!-- Toggle Completion -->
                        <form action="/todos/{{ $todo->id }}" method="POST" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                            @csrf
                            @method('PUT')
                            <input type="checkbox" name="is_completed" onChange="this.form.submit()" {{ $todo->is_completed ? 'checked' : '' }} style="cursor: pointer; width: 1.25rem; height: 1.25rem;">
                            <span style="color: {{ $todo->is_completed ? '#9ca3af' : '#1f2937' }}; text-decoration: {{ $todo->is_completed ? 'line-through' : 'none' }}; font-size: 0.95rem;">
                                {{ $todo->title }}
                            </span>
                        </form>
                        
                        <!-- Delete Form -->
                        <form action="/todos/{{ $todo->id }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background-color: transparent; color: #ef4444; width: auto; padding: 0.25rem 0.5rem; margin: 0; font-size: 0.875rem; border: 1px solid #fca5a5; display: flex; align-items: center; gap: 0.25rem;">
                                Delete
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <p style="text-align: center; color: #6b7280; font-size: 0.9rem;">No tasks yet. Create one above!</p>
        @endif
    </div>
    @else
    <div class="container">
        <h2>Create an Account</h2>
        <form action="/register" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required placeholder="John Doe">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="john@example.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="/login">Log in</a>
        </div>
    </div>
    @endauth
</body>
</html>