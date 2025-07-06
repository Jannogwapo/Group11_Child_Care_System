<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOG IN</title>
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <h1>CHILDCARE+<br>SYSTEM</h1>
        </div>
        <div class="form-section">
            <div class="form-header">
                <img src="{{ asset('images/logo2.png') }}" alt="Homecare Center for Children" class="header-logo">
            </div>
            <form action="{{ route('login.post') }}" method="POST">
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

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="input-group">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                </div>
                
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" id="submitBtn">LOGIN</button>
                
                <p class="register-link">Don't Have an Account? <a href="{{ route('register') }}">Register</a></p>
            </form>
        </div>
    
</body>
</html>


