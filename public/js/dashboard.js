document.addEventListener('DOMContentLoaded', function () {
    // Once the content is loaded, hide the loading overlay
    const loadingOverlay = document.getElementById('loading-overlay');
    
    // Add a slight delay to make the overlay visible during the loading phase
    setTimeout(function () {
        loadingOverlay.style.visibility = 'hidden';
        loadingOverlay.style.opacity = 0;
    }, 300);
    
    const profilePicture = document.getElementById('profile-picture-container');
    const profileImgInput = document.getElementById('user_profile_picture');
    const uploadUrl = profilePicture.getAttribute('data-url'); // Get the URL from the data attribute

    // Access the CSRF token from the global window.Laravel object
    const csrfToken = window.Laravel.csrfToken;

    // Create form element
    const profileForm = document.createElement('form');
    profileForm.setAttribute('method', 'POST');
    profileForm.setAttribute('enctype', 'multipart/form-data');
    profileForm.setAttribute('action', uploadUrl); // Set the form action
    
    // Create a hidden CSRF token field and append it to the form
    const csrfInput = document.createElement('input');
    csrfInput.setAttribute('type', 'hidden');
    csrfInput.setAttribute('name', '_token');
    csrfInput.setAttribute('value', csrfToken);
    profileForm.appendChild(csrfInput);

    // Append the file input to the form
    profileForm.appendChild(profileImgInput);

    // Trigger the file input when clicking the profile picture or circle
    profilePicture.addEventListener('click', function () {
        profileImgInput.click();
    });

    // When a file is selected, submit the form automatically
    profileImgInput.addEventListener('change', function () {
        if (profileImgInput.files.length > 0) {
            // Append the form to the body (or hidden container) before submitting
            document.body.appendChild(profileForm);

            // Submit the form
            profileForm.submit();
        }
    });
});
