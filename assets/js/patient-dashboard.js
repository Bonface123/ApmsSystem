// Function to show a specific section and hide the others
function showSection(sectionId) {
    const sections = document.querySelectorAll('.dashboard-section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    document.getElementById(sectionId).style.display = 'block';
}

// Load Medical Records with AI-Driven Descriptions
function loadMedicalRecords() {
    fetch('http://localhost:8000/api/medical-records/')
        .then(response => response.json())
        .then(data => {
            const medicalRecordsDiv = document.getElementById('medicalRecords');
            medicalRecordsDiv.innerHTML = '';  // Clear existing data
            data.forEach(record => {
                const recordDiv = document.createElement('div');
                recordDiv.classList.add('medical-record');
                recordDiv.innerHTML = `
                    <h3>${record.title}</h3>
                    <p>${record.description}</p>
                    <p><strong>AI Explanation:</strong> ${record.aiExplanation}</p>
                `;
                medicalRecordsDiv.appendChild(recordDiv);
            });
        })
        .catch(error => console.error('Error loading medical records:', error));
}

// Load Patient Appointments
function loadAppointments() {
    fetch('http://localhost:8000/api/appointments/')
        .then(response => response.json())
        .then(data => {
            const appointmentsList = document.getElementById('appointmentsList');
            appointmentsList.innerHTML = '';  // Clear existing data
            data.forEach(appointment => {
                const appointmentDiv = document.createElement('div');
                appointmentDiv.classList.add('appointment');
                appointmentDiv.innerHTML = `
                    <p><strong>Date:</strong> ${appointment.date}</p>
                    <p><strong>Reason:</strong> ${appointment.reason}</p>
                    <button onclick="cancelAppointment(${appointment.id})">Cancel Appointment</button>
                `;
                appointmentsList.appendChild(appointmentDiv);
            });
        })
        .catch(error => console.error('Error loading appointments:', error));
}

// Load Patient Prescriptions
function loadPrescriptions() {
    fetch('http://localhost:8000/api/prescriptions/')
        .then(response => response.json())
        .then(data => {
            const prescriptionsList = document.getElementById('prescriptionsList');
            prescriptionsList.innerHTML = '';  // Clear existing data
            data.forEach(prescription => {
                const prescriptionDiv = document.createElement('div');
                prescriptionDiv.classList.add('prescription');
                prescriptionDiv.innerHTML = `
                    <p><strong>Medication:</strong> ${prescription.medication}</p>
                    <p><strong>Dosage:</strong> ${prescription.dosage}</p>
                    <p><strong>Duration:</strong> ${prescription.duration}</p>
                `;
                prescriptionsList.appendChild(prescriptionDiv);
            });
        })
        .catch(error => console.error('Error loading prescriptions:', error));
}

// Update Patient Profile
function updateProfile() {
    const profileAge = document.getElementById('profileAge').value;
    const profileMedicalHistory = document.getElementById('profileMedicalHistory').value;

    fetch('http://localhost:8000/api/profile/update/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            age: profileAge,
            medical_history: profileMedicalHistory,
        }),
    })
    .then(response => response.json())
    .then(data => {
        alert('Profile updated successfully!');
        console.log(data);
    })
    .catch(error => console.error('Error updating profile:', error));
}
