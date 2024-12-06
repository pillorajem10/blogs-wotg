// posts.js

document.addEventListener('DOMContentLoaded', function () {
    // Once the content is loaded, hide the loading overlay
    const loadingOverlay = document.getElementById('loading-overlay');
    
    // Add a slight delay to make the overlay visible during the loading phase
    setTimeout(function () {
        loadingOverlay.style.visibility = 'hidden';
        loadingOverlay.style.opacity = 0;
    }, 300);

    // Get the modal
    var modal = document.getElementById("addPostModal");

    // Get the button that opens the modal
    var btn = document.getElementById("addPostBtn");

    // Get the <span> element that closes the modal
    var closeBtn = document.getElementById("closeModalBtn");

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Attach likePost event to all like buttons
    $(".like-btn").on("click", function () {
        var postId = $(this).data("post-id");
        likePost(postId);
    });
});

// AJAX function to handle Like/Unlike
function likePost(postId) {
    $.ajax({
        url: '/community/' + postId + '/like',
        type: 'POST',
        data: {
            _token: window.Laravel.csrfToken // Use the csrfToken from the window object
        },
        success: function(response) {
            // Update the like button text (toggle between Like and Unlike)
            var button = $("#post-" + postId).find(".like-btn");
            button.text(response.likesCount > 0 ? "Liked" : "Like");

            // Update the like count
            $("#likes-count-" + postId).text(response.likesCount);
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });
}
