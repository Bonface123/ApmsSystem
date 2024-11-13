<?php
session_start();
require 'db.php'; // Database connection

// Enable MySQLi exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Redirect logged-in users away from registration/login
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Capture the role from the URL, default to 'patient' if not set
$role = isset($_GET['role']) ? $_GET['role'] : 'patient';

// Validate role
$valid_roles = ['patient', 'doctor', 'finance'];
if (!in_array($role, $valid_roles)) {
    $role = 'patient';
}

// Initialize variables
$error = '';
$success = '';

// Ensure the $action variable is initialized
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Capture additional data if the role is 'patient' and only during registration
    if ($action === 'register') {
        $age = isset($_POST['age']) ? trim($_POST['age']) : '';
        $medical_history = isset($_POST['medical_history']) ? trim($_POST['medical_history']) : '';
    }

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!in_array($role, $valid_roles)) {
        $error = "Invalid user role.";
    } elseif ($role === 'patient' && $action === 'register' && (empty($age) || empty($medical_history))) {
        $error = "Age and medical history are required for patients during registration.";
    } elseif ($role === 'patient' && $action === 'register' && (!is_numeric($age) || $age <= 0)) {
        $error = "Please provide a valid age.";
    } else {
        try {
            if ($action === 'register') {
                // Check if username or email already exists
                $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    // Determine if it's username or email duplication
                    $stmt->bind_result($existing_username, $existing_email);
                    $stmt->fetch();
                    if ($existing_username === $username) {
                        $error = "Username already exists.";
                    }
                    if ($existing_email === $email) {
                        $error = "Email already registered.";
                    }
                } else {
                    // Register new user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    if ($role === 'patient') {
                        // Insert age and medical history only for 'patient' registration
                        $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password, role, age, medical_history) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt_insert->bind_param("ssssss", $username, $email, $hashed_password, $role, $age, $medical_history);
                    } else {
                        $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                        $stmt_insert->bind_param("ssss", $username, $email, $hashed_password, $role);
                    }

                    $stmt_insert->execute();
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    header("Location: dashboard.php");
                    exit();
                }
                $stmt->close();
            } elseif ($action === 'login') {
                // Login user
                $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($hashed_password, $user_role);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        if ($user_role === $role) {
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = $user_role;
                            header("Location: dashboard.php");
                            exit();
                        } else {
                            $error = "Incorrect role selected.";
                        }
                    } else {
                        $error = "Invalid password.";
                    }
                } else {
                    $error = "User not found.";
                }
                $stmt->close();
            } else {
                $error = "Invalid action.";
            }
        } catch (mysqli_sql_exception $e) {
            // Handle database errors gracefully
            $error = "An unexpected error occurred. Please try again later.";
            // Log the actual error message for debugging
            error_log($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login/Register - APMS</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Specific styles for login_register.php */
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        .auth-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1e88e5;
        }
        .auth-container form {
            display: flex;
            flex-direction: column;
        }
        .auth-container label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .auth-container input,
        .auth-container textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .auth-container .buttons {
            display: flex;
            justify-content: space-between;
        }
        .auth-container .buttons button {
            width: 48%;
            padding: 10px;
            background-color: #1e88e5;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .auth-container .buttons button:hover {
            background-color: #1565c0;
        }
        .error-message, .success-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2><?php echo ucfirst($role); ?> Login / Register</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <?php if ($role === 'patient' && $action === 'register'): ?>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>

                <label for="medical_history">Medical History:</label>
                <textarea id="medical_history" name="medical_history" required></textarea>
            <?php endif; ?>

            <div class="buttons">
                <button type="submit" name="action" value="login">Login</button>
                <button type="submit" name="action" value="register">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
