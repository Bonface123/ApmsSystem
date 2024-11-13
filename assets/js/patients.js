// Wait until the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
    
    // Patient Login Form Submission
    const loginForm = document.querySelector("#loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent form submission for now
            
            const username = document.querySelector("#loginUsername").value;
            const password = document.querySelector("#loginPassword").value;

            if (username === "" || password === "") {
                alert("Please fill in both fields");
            } else {
                // Simulate form submission
                console.log("Login Form Submitted with", { username, password });
                // Uncomment below to allow actual form submission
                // loginForm.submit();
            }
        });
    }

    // Patient Registration Form Submission
    const registerForm = document.querySelector("#registrationForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent form submission for now
            
            const username = document.querySelector("#registerUsername").value;
            const password = document.querySelector("#registerPassword").value;
            const age = document.querySelector("#age").value;
            const medicalHistory = document.querySelector("#medical_history").value;

            if (username === "" || password === "" || age === "") {
                alert("Please fill in all required fields");
            } else {
                // Simulate form submission
                console.log("Registration Form Submitted with", {
                    username,
                    password,
                    age,
                    medicalHistory
                });
                // Uncomment below to allow actual form submission
                // registerForm.submit();
            }
        });
    }
});
