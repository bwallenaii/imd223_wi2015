<html>
    <head>
        <title>Sign Up/Login</title>
    </head>
    <style>
        label{
            display: block;
        }
    </style>
    <body>
    
        <form action="signup.php" method="post" enctype="multipart/form-data">
            <h1>Sign up</h1>
            <label>
                Username: <input type="text" name="username" />
            </label>
            <label>
                Password: <input type="password" name="password" />
            </label>
            <label>
                Confirm Password: <input type="password" name="confirm_password" />
            </label>
            <button type="submit">Register</button>
        </form>
        
        <form action="login.php" method="post" enctype="multipart/form-data">
            <h1>Sign in</h1>
            <label>
                Username: <input type="text" name="username" />
            </label>
            <label>
                Password: <input type="password" name="password" />
            </label>
            <button type="submit">Login</button>
        </form>
    </body>
</html>