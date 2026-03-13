
<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        header("Location: /atud/ui/0index.php");
        exit();
    } else {
        $message = "Incorrect email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #ffffff;
            padding: 40px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #333;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .login-container input:focus {
            border-color: #4e73df;
            outline: none;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background: #4e73df;
            border: none;
            color: white;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-container button:hover {
            background: #2e59d9;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .signup-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .signup-link a {
            color: #4e73df;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>LOGIN</h2>

    <?php if (!empty($message)) : ?>
        <div class="error-message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
    </form>

    <div class="signup-link">
        Don't have an account? <a href="signup.php">Sign Up</a>
    </div>
</div>

</body>
</html>
