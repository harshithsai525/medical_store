<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .login-container, .registration-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .registration-container {
            display: none; /* Initially hide the registration container */
        }

        h2 {
            text-align: center;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-text {
            color: #ff0000;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mydatabase";

    $forwarding = false;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process registration form data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['register'])) {
            $newUsername = $_POST['newUsername'];
            $newPassword = $_POST['newPassword'];

            // Check if username already exists
            $checkUsernameQuery = "SELECT * FROM users WHERE username = '$newUsername'";
            $result = $conn->query($checkUsernameQuery);

            if ($result->num_rows > 0) {
                // Username already exists
                echo "<script>alert('Username already exists. Please choose a different username.');</script>";
            } else {
                // Insert new user into database
                $insertUserQuery = "INSERT INTO users (username, passkey) VALUES ('$newUsername', '$newPassword')";

                if ($conn->query($insertUserQuery) === TRUE) {
                    echo "<script>alert('User registered successfully!');</script>";
                } else {
                    echo "Error: " . $insertUserQuery . "<br>" . $conn->error;
                }
            }
        } elseif (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Validate user credentials
            $sql = "SELECT * FROM users WHERE username = '$username' AND passkey = '$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Password is correct, login successful
                echo "<script>alert('Login successful!');</script>";
                $forwarding = true;
                // Redirect to dashboard or desired page
            } else {
                echo "<script>alert('Invalid username or password');</script>";
            }

            if($forwarding){
                header("Location: index.php");
            }
        }
    }

    // Close database connection
    $conn->close();
    ?>
    
    <div class="login-container" id="loginContainer">
        <h2>Login</h2>
        <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Login</button>
            <p id="loginMessage" class="error-text"></p>
        </form>
        <p>Don't have an account? <a href="#" onclick="showRegistration()">Register here</a></p>
    </div>

    <div class="registration-container" id="registrationContainer">
        <h2>Register</h2>
        <form id="registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group">
                <label for="newUsername">New Username</label>
                <input type="text" id="newUsername" name="newUsername" required>
            </div>
            <div class="input-group">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="newPassword" required>
            </div>
            <button type="submit" name="register">Register</button>
            <p id="registerMessage" class="error-text"></p>
        </form>
        <p>Already have an account? <a href="#" onclick="showLogin()">Login here</a></p>
    </div>

    <script>
        function showRegistration() {
            document.getElementById('loginContainer').style.display = 'none';
            document.getElementById('registrationContainer').style.display = 'block';
        }

        function showLogin() {
            document.getElementById('registrationContainer').style.display = 'none';
            document.getElementById('loginContainer').style.display = 'block';
        }
    </script>
</body>
</html>
