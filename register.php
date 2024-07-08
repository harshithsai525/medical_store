<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['newUsername'];
    $newPassword = $_POST['newPassword'];

    $checkUsernameQuery = "SELECT * FROM users WHERE username = '$newUsername'";
    $result = $conn->query($checkUsernameQuery);

    if ($result->num_rows > 0) {
        echo "Username already exists. Please choose a different username.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $insertUserQuery = "INSERT INTO users (username, passkey) VALUES ('$newUsername', '$hashedPassword')";

        if ($conn->query($insertUserQuery) === TRUE) {
            echo "User registered successfully!";
        } else {
            echo "Error: " . $insertUserQuery . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
