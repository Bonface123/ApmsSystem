<?php
session_start();

// Check if the user has finance permissions
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'finance') {
    header("Location: login.php");
    exit();
}

// Determine the current time to create a personalized greeting
$hour = date("H");
if ($hour >= 5 && $hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour >= 12 && $hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}

require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patient Billing</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo $greeting . ", " . "Welcome " . htmlspecialchars($_SESSION['username']) . " to the Finance  Department!"; ?></h1>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="finance_billing.php">Billing Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <!-- Display Success or Error Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert error">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Form to Add or Update Billing -->
        <section id="billingForm">
            <h2>Add / Update Billing Information</h2>
            <form action="add_update_billing.php" method="post">
                <label for="patient_name">Select Patient:</label>
                <select id="patient_name" name="patient_name" required>
                    <option value="">Select a patient</option>
                    <?php
                    // Fetch patients from the users table with role "patient"
                    $patientResult = $conn->query("SELECT id, username FROM users WHERE role = 'patient'");
                    while ($patient = $patientResult->fetch_assoc()) {
                        echo "<option value='{$patient['username']}'>{$patient['username']}</option>";
                    }
                    ?>
                </select>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" required>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                </select>

                <button type="submit">Submit</button>
            </form>
        </section>

        <!-- Display Existing Billing Records -->
        <section id="existingBilling">
            <h2>Existing Billing Records</h2>
            <table>
                <tr>
                    <th>Patient Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php
                // Updated SQL query to use user_id for the join
                $sql = "SELECT u.username AS patient_name, b.amount, b.date, b.status
                        FROM billing_info AS b
                        JOIN users AS u ON b.user_id = u.id
                        WHERE u.role = 'patient'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['patient_name']}</td>
                                <td>\${$row['amount']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['status']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No billing records found</td></tr>";
                }

                $conn->close();
                ?>
            </table>
        </section>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>
</body>
</html>
