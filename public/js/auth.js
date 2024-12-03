document.addEventListener('DOMContentLoaded', function() {
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
