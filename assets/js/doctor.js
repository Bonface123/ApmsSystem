// Wait until the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {

    // Doctor Login Form Submission
    const doctorLoginForm = document.querySelector("#doctorLoginForm");
    if (doctorLoginForm) {
        doctorLoginForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent form submission for now
            
            const username = document.querySelector("#doctorLoginUsername").value;
            const password = document.querySelector("#doctorLoginPassword").value;

            if (username === "" || password === "") {
                alert("Please fill in both fields");
            } else {
                // Simulate form submission
                console.log("Doctor Login Form Submitted with", { username, password });
                // Uncomment below to allow actual form submission
                // doctorLoginForm.submit();
            }
        });
    }

    // Future code for managing appointments can be added here
});
