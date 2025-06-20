<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Homecare Center for Children</title>
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
</head>
<body>
    <div class="register-container">
        <div class="logo-section">
            <h1>CHILDCARE+<br>SYSTEM</h1>
        </div>
        <div class="form-section">
            <div class="form-header">
                <img src="{{ asset('images/logo2.png') }}" alt="Homecare Center for Children" class="header-logo">
            </div>
            <form action="{{ route('register.store') }}" method="POST" id="registerForm">
                @csrf
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                </div>

                <div class="input-group">
                    <select name="position" id="position" required>
                        <option value="">Select Your Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <select name="gender" id="gender" required>
                        <option value="">Select Your Gender</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->id }}">{{ $gender->gender_name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-submit">REGISTER</button>
                
                <p class="login-link">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </form>
        </div>
    </div>
    <script src="{{asset('js/register.js')}}"></script>
</body>
</html>



