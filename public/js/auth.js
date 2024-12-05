document.addEventListener('DOMContentLoaded', function() {
    const loadingOverlay = document.getElementById('loading-overlay');
    
    // Show the loading overlay
    loadingOverlay.style.visibility = 'visible';
    loadingOverlay.style.opacity = 1;
    
    // You can optionally disable the submit button to prevent double-clicks
    const submitButton = event.target.querySelector('.auth-button');
    submitButton.disabled = true;

    // After the form is submitted, you might want to hide the overlay after a delay
    setTimeout(function () {
        // Assuming you handle form submission asynchronously, hide the overlay
        loadingOverlay.style.visibility = 'hidden';
        loadingOverlay.style.opacity = 0;
        
        // Re-enable the submit button if needed
        submitButton.disabled = false;
    });
    
    
    // Get the select elements and their associated note divs
    const dgroupLeaderSelect = document.getElementById('user_already_a_dgroup_leader');
    const dgroupMemberSelect = document.getElementById('user_already_a_dgroup_member');
    const leaderNoteDiv = document.getElementById('dgroup-leader-note');
    const memberNoteDiv = document.getElementById('dgroup-member-note');

    // Check the initial state for both dropdowns and show notes if needed
    if (dgroupLeaderSelect.value === '1') {
        leaderNoteDiv.style.display = 'block';
    }
    
    if (dgroupMemberSelect.value === '1') {
        memberNoteDiv.style.display = 'block';
    }

    // Add event listeners to toggle the note visibility based on selection
    dgroupLeaderSelect.addEventListener('change', function() {
        if (dgroupLeaderSelect.value === '1') {
            leaderNoteDiv.style.display = 'block'; // Show leader note
        } else {
            leaderNoteDiv.style.display = 'none'; // Hide leader note
        }
    });

    dgroupMemberSelect.addEventListener('change', function() {
        if (dgroupMemberSelect.value === '1') {
            memberNoteDiv.style.display = 'block'; // Show member note
        } else {
            memberNoteDiv.style.display = 'none'; // Hide member note
        }
    });
});

document.getElementById('profile-auth-form').addEventListener('submit', function(event) {
    const loadingOverlay = document.getElementById('loading-overlay');
    
    // Show the loading overlay when the form is submitted
    loadingOverlay.style.visibility = 'visible';
    loadingOverlay.style.opacity = 1;

    // Optionally disable the submit button to prevent multiple submissions
    const submitButton = event.target.querySelector('.auth-button');
    submitButton.disabled = true;

    // If the form is processed asynchronously (e.g., via AJAX), you can hide the overlay once processing is complete
    setTimeout(function () {
        // Hide the overlay after a delay or once the form submission is done
        loadingOverlay.style.visibility = 'hidden';
        loadingOverlay.style.opacity = 0;
        
        // Re-enable the submit button if needed
        submitButton.disabled = false;
    }, 3000); // Adjust the timeout as necessary
});
