<?php
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Fetch input values
$symptoms = $data['symptoms'];
$age = $data['age'];
$weight = $data['weight'];
$gender = $data['gender'];
$allergies = $data['allergies'];

// Function to simulate AI suggestions with hardcoded data
function get_ai_suggestions($symptoms, $age, $weight, $gender, $allergies) {
    // Initialize an array to hold the medication suggestions
    $suggestions = [];

    // Age-based logic for medication dosage adjustments
    $age_adjustment = '';
    $dosage_multiplier = 1;  // Default multiplier for adults

    if ($age < 12) {
        $age_adjustment = ' (children)';
        $dosage_multiplier = 0.5;  // Children get a reduced dosage (adjust as needed)
    } elseif ($age > 65) {
        $age_adjustment = ' (elderly)';
        $dosage_multiplier = 0.75;  // Elderly may have reduced dosages (adjust as needed)
    }

    // Weight-based logic for medication dosage adjustments
    $weight_adjustment = '';
    if ($weight < 50) {
        $weight_adjustment = ' (low weight)';
        $dosage_multiplier *= 0.8;  // Further reduce dosage for low-weight patients
    } elseif ($weight > 100) {
        $weight_adjustment = ' (high weight)';
        $dosage_multiplier *= 1.2;  // Increase dosage for high-weight patients
    }

    // Gender-based medication considerations (example logic)
    $gender_adjustment = '';
    if ($gender === 'female') {
        $gender_adjustment = ' (female-specific dosages)';
    } elseif ($gender === 'male') {
        $gender_adjustment = ' (male-specific dosages)';
    }

    // Example hardcoded suggestions based on symptoms
    if (stripos($symptoms, "fever") !== false) {
        $suggestions[] = [
            'medication' => 'Paracetamol' . $age_adjustment . $weight_adjustment . $gender_adjustment,
            'dosage' => (500 * $dosage_multiplier) . 'mg',
            'duration' => '5 days'
        ];
        $suggestions[] = [
            'medication' => 'Ibuprofen' . $age_adjustment . $weight_adjustment . $gender_adjustment,
            'dosage' => (200 * $dosage_multiplier) . 'mg',
            'duration' => '3 days'
        ];
    }

    // Additional symptom checks
    if (stripos($symptoms, "cough") !== false) {
        $suggestions[] = [
            'medication' => 'Dextromethorphan' . $age_adjustment . $weight_adjustment . $gender_adjustment,
            'dosage' => (10 * $dosage_multiplier) . 'mg',
            'duration' => '5 days'
        ];
        $suggestions[] = [
            'medication' => 'Guaifenesin' . $age_adjustment . $weight_adjustment . $gender_adjustment,
            'dosage' => (200 * $dosage_multiplier) . 'mg',
            'duration' => '5 days'
        ];
    }

    if (stripos($symptoms, "headache") !== false) {
        $suggestions[] = [
            'medication' => 'Aspirin' . $age_adjustment . $weight_adjustment . $gender_adjustment,
            'dosage' => (500 * $dosage_multiplier) . 'mg',
            'duration' => '3 days'
        ];
        $suggestions[] = [
            'medication' => 'Acetaminophen' . $age_adjustment . $weight_adjustment . $gender_adjustment,
            'dosage' => (500 * $dosage_multiplier) . 'mg',
            'duration' => '3 days'
        ];
    }

    if (stripos($symptoms, "nausea") !== false) {
        $suggestions[] = [
            'medication' => 'Ondansetron' . $age_adjustment . $weight_adjustment . $gender_adjustment,
            'dosage' => (4 * $dosage_multiplier) . 'mg',
            'duration' => 'as needed'
        ];
        $suggestions[] = [
            'medication' => 'Meclizine' . $age_adjustment . $weight_adjustment . $gender_adjustment,
            'dosage' => (25 * $dosage_multiplier) . 'mg',
            'duration' => 'as needed'
        ];
    }

    // Example hardcoded suggestions based on allergies
    if (stripos($allergies, "milk") !== false) {
        $suggestions[] = [
            'medication' => 'Lactaid',
            'dosage' => '1 tablet',
            'duration' => 'as needed'
        ];
    }

    if (stripos($allergies, "penicillin") !== false) {
        $suggestions[] = [
            'medication' => 'Cephalosporin',
            'dosage' => '250mg',
            'duration' => '7 days'
        ];
    }

    if (stripos($allergies, "pollen") !== false) {
        $suggestions[] = [
            'medication' => 'Cetirizine',
            'dosage' => '10mg',
            'duration' => '7 days'
        ];
        $suggestions[] = [
            'medication' => 'Loratadine',
            'dosage' => '10mg',
            'duration' => '7 days'
        ];
    }

    // Return error if no suggestions
    if (empty($suggestions)) {
        return ["error" => "No suggestions found based on the provided symptoms, age, weight, gender, or allergies."];
    }

    return $suggestions;
}

// Get hardcoded suggestions from function
$suggestions = get_ai_suggestions($symptoms, $age, $weight, $gender, $allergies);

// Return suggestions as JSON
if (isset($suggestions['error'])) {
    echo json_encode(['error' => $suggestions['error']]);
} else {
    echo json_encode(['suggestions' => $suggestions]);
}
?>
