// Get the elements
const termsCheckbox = document.getElementById('terms');
const submitButton = document.getElementById('submit_button');

// Add event listener to checkbox to toggle submit button
termsCheckbox.addEventListener('change', function() {
    submitButton.disabled = !termsCheckbox.checked;
});