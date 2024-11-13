<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include database connection


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
    $username = $_GET['username'];

    // Fetch patient data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if (!$patient) {
        echo "Patient not found!";
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update patient record
    $username = $_POST['username'];
    $age = $_POST['age'];
    $medical_history = $_POST['medical_history'];

    $stmt = $conn->prepare("UPDATE users SET age = ?, medical_history = ? WHERE username = ?");
    $stmt->bind_param("sss", $age, $medical_history, $username);

    if ($stmt->execute()) {
        echo "Patient record updated successfully!";
        header("Location: managePatients.php");
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Record</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Update Patient Record</h1>
    </header>

    <main>
        <form action="update_patient.php" method="POST">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($patient['username']); ?>">

            <label for="age">Age:</label>
            <input type="number" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>" required>

            <label for="medical_history">Medical History:</label>
            <textarea name="medical_history" required><?php echo htmlspecialchars($patient['medical_history']); ?></textarea>

            <button type="submit">Update</button>
        </form>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>
</body>
</html>
