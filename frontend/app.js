// Function to dynamically load sections into the main content area
function loadSection(section) {
    let contentDiv = document.getElementById('content');

    // Map section names to their respective HTML files
    let sectionFiles = {
        'patients': 'patients.html',
        'doctor': 'doctor.html',
        'finance': 'finance.html'
    };

    // Check if the section exists in the map and load it
    if (sectionFiles[section]) {
        fetch(sectionFiles[section])
            .then(response => response.text())  // Parse the response as text
            .then(data => {
                contentDiv.innerHTML = data;  // Inject the HTML content into the main area
                initializeBootstrapComponents();  // Reinitialize Bootstrap components
            })
            .catch(error => console.error('Error loading the section:', error));
    } else {
        console.error('Section not found: ' + section);
    }
}

// Function to reinitialize Bootstrap Modals and other components after content is dynamically loaded
function initializeBootstrapComponents() {
    // Reinitialize Bootstrap Modals
    $('#registerModal').on('shown.bs.modal', function () {
        $('#username').trigger('focus');  // Auto-focus on the username input in the modal
    });

    // Reinitialize other Bootstrap components if needed in the future
}
