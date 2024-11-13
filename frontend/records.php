<?php
// Start the session and ensure the user is authenticated
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';  // Include your database connection

$patient_username = $_SESSION['username'];  // Logged-in patient's username

// Fetch medical records for the logged-in patient
$sql_records = "SELECT * FROM medical_records WHERE patient_username = ? ORDER BY created_at DESC";
$stmt_records = $conn->prepare($sql_records);
$stmt_records->bind_param("s", $patient_username);
$stmt_records->execute();
$medical_records = $stmt_records->get_result();

// Fetch all medical terms and their descriptions
$sql_terms = "SELECT term, description FROM medical_terms";
$terms_result = $conn->query($sql_terms);

$medical_terms = [];
while ($row = $terms_result->fetch_assoc()) {
    $medical_terms[$row['term']] = $row['description'];
}

$stmt_records->close();
$conn->close();

/**
 * Function to parse text and wrap medical terms with tooltip functionality
 *
 * @param string $text The text to parse
 * @param array $terms An associative array of medical terms and their descriptions
 * @return string The parsed HTML with tooltips
 */
function parseMedicalTerms($text, $terms) {
    foreach ($terms as $term => $description) {
        // Use word boundaries to match exact terms, case-insensitive
        $pattern = '/\b(' . preg_quote($term, '/') . ')\b/i';
        $replacement = '<span class="medical-term" aria-describedby="tooltip">' . '$1' . '<span class="tooltip" role="tooltip">' . htmlspecialchars($description) . '</span>' . '</span>';
        $text = preg_replace($pattern, $replacement, $text);
    }
    return $text;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Medical Records</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Tooltip styling */
        .medical-term {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
            position: relative;
        }

        .tooltip {
            display: none;
            position: absolute;
            background-color: #333;
            color: #fff;
            padding: 8px;
            border-radius: 5px;
            z-index: 1000;
            width: 250px;
            top: -5px;
            left: 105%;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        .medical-term:hover .tooltip {
            display: block;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .tooltip {
                position: static;
                width: 100%;
                margin-top: 5px;
            }
        }

        /* Accessibility focus styles */
        .medical-term:focus .tooltip {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Medical Records</h1>
    </header>
 <!-- Navigation Bar -->
 <nav>
    <ul>
        <li><a href="patient_dashboard.php">Home</a></li>
        <li><a href="patient_appointments.php">Schedule Appointment</a></li>
        <li><a href="patient_view_appointments.php">View Appointments</a></li> <!-- Combined View -->
        <li><a href="prescriptions.php">View Prescriptions</a></li>
        <li><a href="view_billing.php">Manage Billing</a></li>
        <li><a href="records.php">Medical Records</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>


    <!-- Main Content -->
    <main>
        <?php if ($medical_records->num_rows > 0): ?>
            <?php while ($record = $medical_records->fetch_assoc()): ?>
                <section>
                    <h2>Record ID: <?php echo htmlspecialchars($record['record_id']); ?></h2>
                    <p><strong>Diagnosis:</strong> 
                        <?php 
                        echo !empty($record['diagnosis']) 
                            ? parseMedicalTerms($record['diagnosis'], $medical_terms) 
                            : 'No diagnosis available'; 
                        ?>
                    </p>
                    <p><strong>Treatment:</strong> 
                        <?php 
                        echo !empty($record['treatment']) 
                            ? parseMedicalTerms($record['treatment'], $medical_terms) 
                            : 'No treatment available'; 
                        ?>
                    </p>
                    <p><strong>Notes:</strong> 
                        <?php 
                        echo !empty($record['record_notes']) 
                            ? parseMedicalTerms($record['record_notes'], $medical_terms) 
                            : 'No notes available'; 
                        ?>
                    </p>
                    <p><em>Recorded on: <?php echo htmlspecialchars($record['created_at']); ?></em></p>
                </section>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have no medical records yet.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>

    <script>
        // Optional: Enhance tooltip behavior with JavaScript if needed
        // For example, you can add event listeners for better accessibility
    </script>
</body>
</html>
