<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
</head>
<body>
    <div class="container">
        <h1>Registration</h1>
        <form action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label for="position">Select Your Role:</label>
                <select name="position" id="position" required>
                    <option value="">-- Choose Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="gender">Select Your Gender:</label>
                <select name="gender" id="gender" required>
                    <option value="">-- Choose Gender --</option>
                    @foreach($genders as $gender)
                        <option value="{{ $gender->id }}">{{ $gender->gender_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </div>
            
            <button type="submit" class="btn-submit">Register</button>
        </form>
    </div>
</body>
</html>