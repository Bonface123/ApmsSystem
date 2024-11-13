document.getElementById("get-suggestions").addEventListener("click", function() {
    const symptoms = document.getElementById("symptoms").value;
    const age = document.getElementById("age").value;
    const weight = document.getElementById("weight").value;
    const gender = document.getElementById("gender").value;
    const allergies = document.getElementById("allergies").value;

    // Check if symptoms are provided
    if (!symptoms) {
        alert("Please enter symptoms.");
        return;
    }

    // Check if all required fields (age, weight, etc.) are filled
    if (!age || !weight || !gender || !allergies) {
        alert("Please fill in all fields.");
        return;
    }

    // Prepare the data to send to the API
    const requestData = {
        symptoms: symptoms,
        age: age,
        weight: weight,
        gender: gender,
        allergies: allergies
    };

    // Call the AI suggestion API
    fetch("ai_suggestions_api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        // Assuming the response contains an array of suggestions
        const suggestionsContainer = document.getElementById("ai-suggestions");
        suggestionsContainer.innerHTML = ""; // Clear previous suggestions

        if (data.suggestions && data.suggestions.length > 0) {
            data.suggestions.forEach(suggestion => {
                const suggestionElement = document.createElement("p");
                suggestionElement.textContent = `${suggestion.medication} - Dosage: ${suggestion.dosage}, Duration: ${suggestion.duration}`;
                suggestionsContainer.appendChild(suggestionElement);
            });

            // Store the AI suggestions in a hidden field for submission
            document.getElementById("ai_suggestions_input").value = data.suggestions.map(s => s.medication).join(", ");
        } else {
            suggestionsContainer.innerHTML = "<p>No suggestions found.</p>";
        }
    })
    .catch(error => {
        console.error("Error fetching AI suggestions:", error);
        alert("Failed to get AI suggestions.");
    });
});
