// Function to load content dynamically based on the selected section
function loadSection(section) {
    const content = document.getElementById('content');

    // Clear current content
    content.innerHTML = '';

    // Load the content based on the section selected
    fetch(`${section}.php`)
        .then(response => response.text())
        .then(data => {
            content.innerHTML = data;
            initializeSectionScripts(section);  // Load specific scripts for the section
        })
        .catch(error => console.error('Error loading section:', error));
}

// Initialize specific JavaScript functionality for each section
function initializeSectionScripts(section) {
    if (section === 'appointments') {
        // Logic for viewing appointments
    } else if (section === 'scheduleAppointment') {
        // Logic for scheduling appointments
    } else if (section === 'managePatients') {
        // Logic for managing patients
    } else if (section === 'prescribeMedications') {
        // Logic for prescribing medications
    }
}
