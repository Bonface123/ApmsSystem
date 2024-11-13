<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: login_register.php');
    exit();
}

$role = $_SESSION['role'];

switch ($role) {
    case 'patient':
        header('Location: patient_dashboard.php');
        break;
    case 'doctor':
        header('Location: doctor_dashboard.php');
        break;
    case 'finance':
        header('Location: finance_billing.php');
        break;
    default:
        // Handle unexpected roles
        header('Location: login_register.php');
        break;
}
exit();
?>
