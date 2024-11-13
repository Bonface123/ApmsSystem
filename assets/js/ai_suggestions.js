function getAISuggestions(medication) {
    // Simulate AI suggestions for potential drug interactions
    const suggestions = {
        "Aspirin": "Aspirin may interact with blood thinners like Warfarin. Consider alternatives like Paracetamol.",
        "Ibuprofen": "Ibuprofen may increase the risk of heart attack or stroke if taken for long periods."
    };

    const suggestionDiv = document.getElementById('ai-suggestions');
    suggestionDiv.innerHTML = '';  // Clear previous suggestions

    if (suggestions[medication]) {
        const suggestionText = document.createElement('p');
        suggestionText.textContent = suggestions[medication];
        suggestionDiv.appendChild(suggestionText);
    }
}
