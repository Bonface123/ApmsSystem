<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: doctor_login.php");
    exit();
}

require 'db.php';  // Include database connection

// Fetch all patients for dropdown
$patients_sql = "SELECT username FROM users WHERE role='patient'";
$patients_result = $conn->query($patients_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescribe Medications</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles omitted for brevity */
    </style>
</head>
<body>
    <header>
        <h1>Prescribe Medications</h1>
    </header>

    <!-- Sidebar Navigation -->
    <nav>
        <ul>
            <li><a href="doctor_dashboard.php">Home</a></li>
            <li><a href="view_appointments.php">View Appointments</a></li>
            <li><a href="managePatients.php">Manage Patients</a></li>
            <li><a href="prescribeMedications.php">Prescribe Medications</a></li>
            <li><a href="manage_medical_records.php">Manage Medical Records</a></li>
            <li><a href="add_medical_record.php">Add Medical Records</a></li>
            <li><a href="doctor_logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main>
        <form method="POST" action="process_prescription.php" id="prescription-form">
            <label for="patient">Select Patient:</label>
            <select id="patient" name="patient" required>
                <?php if ($patients_result->num_rows > 0) : ?>
                    <?php while ($row = $patients_result->fetch_assoc()) : ?>
                        <option value="<?php echo $row['username']; ?>"><?php echo $row['username']; ?></option>
                    <?php endwhile; ?>
                <?php else : ?>
                    <option value="">No patients available</option>
                <?php endif; ?>
            </select>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label for="weight">Weight (kg):</label>
            <input type="number" id="weight" name="weight">

            <label for="allergies">Known Allergies:</label>
            <textarea id="allergies" name="allergies"></textarea>

            <label for="symptoms">Symptoms:</label>
            <textarea id="symptoms" name="symptoms" required></textarea>

            <!-- Billing Amount Field -->
            <label for="billing_amount">Billing Amount:</label>
            <input type="number" id="billing_amount" name="billing_amount" required>

            <!-- Button to trigger AI suggestion -->
            <button type="button" id="get-suggestions">Get AI Suggestions</button>

            <!-- AI Suggestions will be displayed here -->
            <div id="ai-suggestions"></div>

            <!-- Hidden field to store AI suggestions -->
            <textarea id="ai_suggestions_input" name="ai_suggestions" hidden></textarea>

            <!-- Section for multiple medications -->
            <div id="medications-section">
                <label>Medications:</label>
                <div class="medication-entry">
                    <input type="text" name="medications[]" placeholder="Medication Name" required>
                    <input type="text" name="dosages[]" placeholder="Dosage" required>
                    <input type="number" name="durations[]" placeholder="Duration (days)" required>
                </div>
            </div>

            <!-- Button to add more medications -->
            <button type="button" id="add-medication">Add Another Medication</button>

            <button type="submit">Submit Prescription</button>
        </form>

    </main>

    <footer>
        <p>© 2024 AI-Enhanced Patient Management System</p>
    </footer>

    <script>
        // Add more medication fields
        document.getElementById('add-medication').addEventListener('click', function() {
            const medicationSection = document.getElementById('medications-section');
            const newEntry = document.createElement('div');
            newEntry.classList.add('medication-entry');
            newEntry.innerHTML = `
                <input type="text" name="medications[]" placeholder="Medication Name" required>
                <input type="text" name="dosages[]" placeholder="Dosage" required>
                <input type="number" name="durations[]" placeholder="Duration (days)" required>
            `;
            medicationSection.appendChild(newEntry);
        });

        // Simulated AI suggestions (you might replace this with actual AI call)
        document.getElementById('get-suggestions').addEventListener('click', function() {
            const aiSuggestions = "Suggested treatment based on symptoms...";
            document.getElementById('ai-suggestions').textContent = aiSuggestions;
            document.getElementById('ai_suggestions_input').value = aiSuggestions;
        });
    </script>

    <script>
        // JavaScript to add more medication fields
        document.getElementById('add-medication').addEventListener('click', function() {
            const medicationsSection = document.getElementById('medications-section');
            const newEntry = document.createElement('div');
            newEntry.classList.add('medication-entry');
            newEntry.innerHTML = `
                <input type="text" name="medications[]" placeholder="Medication Name" required>
                <input type="text" name="dosages[]" placeholder="Dosage" required>
                <input type="number" name="durations[]" placeholder="Duration (days)" required>
            `;
            medicationsSection.appendChild(newEntry);
        });
    </script>

    <script>
        document.getElementById('get-suggestions').addEventListener('click', function() {
            const symptoms = document.getElementById('symptoms').value;
            const allergies = document.getElementById('allergies').value;
            const age = document.getElementById('age').value;
            const weight = document.getElementById('weight').value;
            const gender = document.getElementById('gender').value;

            // Use AJAX to fetch AI suggestions from the server
            fetch('get_ai_suggestions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    symptoms: symptoms,
                    allergies: allergies,
                    age: age,
                    weight: weight,
                    gender: gender
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('AI Response:', data);  // Log the full response for inspection
                const suggestionsContainer = document.getElementById('ai-suggestions');
                suggestionsContainer.innerHTML = ''; // Clear previous suggestions

                if (data.error) {
                    suggestionsContainer.textContent = 'Error: ' + data.error;
                    return;
                }

                if (Array.isArray(data.suggestions) && data.suggestions.length > 0) {
                    data.suggestions.forEach(function(suggestion) {
                        const suggestionElement = document.createElement('p');
                        suggestionElement.textContent = `${suggestion.medication} - Dosage: ${suggestion.dosage}, Duration: ${suggestion.duration}`;
                        suggestionsContainer.appendChild(suggestionElement);
                    });
                    // Store AI suggestions in hidden textarea for form submission
                    document.getElementById('ai_suggestions_input').value = JSON.stringify(data.suggestions);
                } else {
                    suggestionsContainer.textContent = 'No valid suggestions found.';
                }
            })
            .catch(error => {
                console.error('Error fetching AI suggestions:', error);
                document.getElementById('ai-suggestions').textContent = 'Error fetching suggestions.';
            });
        });
    </script>
</body>
</html>
