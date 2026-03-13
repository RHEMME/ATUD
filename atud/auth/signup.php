<?php
require_once __DIR__ . "/../config/db_connect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {

        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "This email is already registered. Please use a different email.";
        } else {
            // Hash password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                if ($stmt->execute()) {
                    $message = "Registration completed successfully.";
                } else {
                    $message = "An error occurred during registration. Please try again.";
                }
                $stmt->close();
            } else {
                $message = "Database preparation failed.";
            }
        }
        $check_stmt->close();

    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Signup</title>

<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg,#667eea,#764ba2);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.signup-container{
    background:white;
    padding:40px;
    width:340px;
    border-radius:10px;
    box-shadow:0 8px 20px rgba(0,0,0,0.2);
    text-align:center;
}

.signup-container h2{
    margin-bottom:20px;
}

input[type=text],
input[type=email],
input[type=password]{
    width:100%;
    padding:10px;
    margin:8px 0 15px 0;
    border:1px solid #ccc;
    border-radius:5px;
}

input[type=submit]{
    width:100%;
    padding:10px;
    background:#667eea;
    border:none;
    color:white;
    font-size:16px;
    border-radius:5px;
    cursor:pointer;
}

input[type=submit]:hover{
    background:#5563c1;
}

.message{
    color:red;
    margin-bottom:10px;
}

.login{
    margin-top:15px;
    font-size:14px;
}

.login a{
    color:#667eea;
    text-decoration:none;
}
</style>

</head>

<body>

<div class="signup-container">

<h2>Create Account</h2>

<?php 
if ($message != "") {
    echo "<p class='message'>$message</p>";
}
?>

<form method="POST" action="">
    
    <input type="text" name="username" placeholder="Enter Username" required>

    <input type="email" name="email" placeholder="Enter Email" required>

    <input type="password" name="password" placeholder="Enter Password" required>

    <input type="submit" value="Sign Up">

</form>

<p class="login">Already have an account? <a href="login.php">Login</a></p>

</div>

</body>
</html>