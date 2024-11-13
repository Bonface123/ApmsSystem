<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "patient_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch doctor details
    $sql = "SELECT * FROM doctors WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $doctor = $result->fetch_assoc();

        // Verify password
        // Verify the password
        if (password_verify($password, $doctor['password'])) {
            // Correct credentials
            $_SESSION['doctor'] = $doctor['username'];
            header("Location: doctor_dashboard.html");  // Redirect to the doctor dashboard
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid username!";
    }

    $conn->close();
}
?>


