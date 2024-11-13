<?php
// register.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $age = intval($_POST["age"]);
    $medical_history = trim($_POST["medical_history"]);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to your MySQL database
    $conn = new mysqli("localhost", "root", "", "patient_management_system");

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username already exists
    $sql_check = "SELECT * FROM patients WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "Username already taken";
    } else {
        // Insert new patient into the database
        $sql = "INSERT INTO patients (username, password, age, medical_history) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $username, $hashed_password, $age, $medical_history);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>
