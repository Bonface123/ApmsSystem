<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Information</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Billing Information</h1>
    </header>
    
    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="patient_appointments.php">Schedule Appointment</a></li>
            <li><a href="patient_view_appointments.php">View Appointments</a></li>
            <li><a href="prescriptions.php">View Prescriptions</a></li>
            <li><a href="billing.php">Manage Billing</a></li>
            <li><a href="records.php">Medical Records</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <section id="billingSummary">
            <h2>Billing Summary</h2>
            <h3>Recent Transactions</h3>
            <ul id="transactionsList"></ul>
        </section>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>

    <script>
        async function loadBillingInfo() {
            try {
                const response = await fetch('billing.php');
                const billingData = await response.json();

                const transactionsList = document.getElementById('transactionsList');
                transactionsList.innerHTML = '';

                billingData.forEach(transaction => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <p><strong>Patient Name:</strong> ${transaction.patient_name}</p>
                        <p><strong>Amount:</strong> $${transaction.amount.toFixed(2)}</p>
                        <p><strong>Date:</strong> ${transaction.date}</p>
                        <p><strong>Status:</strong> ${transaction.status}</p>
                    `;
                    transactionsList.appendChild(li);
                });
            } catch (error) {
                console.error('Error fetching billing information:', error);
            }
        }

        loadBillingInfo();
    </script>
</body>
</html>
