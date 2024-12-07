// posts.js

document.addEventListener('DOMContentLoaded', function () {
    // Once the content is loaded, hide the loading overlay
    const loadingOverlay = document.getElementById('loading-overlay');
    
    // Add a slight delay to make the overlay visible during the loading phase
    setTimeout(function () {
        loadingOverlay.style.visibility = 'hidden';
        loadingOverlay.style.opacity = 0;
    }, 300);

    // Get all comment buttons and modals
    const commentButtons = document.querySelectorAll(".comment-btn");
    const modals = document.querySelectorAll(".modal");
    const closeButtons = document.querySelectorAll(".close");

    // Open modal on comment button click
    commentButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
            const postId = this.getAttribute("data-post-id");
            const modal = document.getElementById(`commentModal-${postId}`);
            if (modal) {
                modal.style.display = "block";
            }
        });
    });

    // Close modal on close button click
    closeButtons.forEach((close) => {
        close.addEventListener("click", function () {
            const postId = this.getAttribute("data-post-id");
            const modal = document.getElementById(`commentModal-${postId}`);
            if (modal) {
                modal.style.display = "none";
            }
        });
    });

    // Close modal on clicking outside of it
    window.onclick = function (event) {
        modals.forEach((modal) => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    };


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
            // Update the like button text and icon based on whether the user liked the post
            var button = $("#post-" + postId).find(".like-btn");
            var icon = button.find("i");

            if (response.likedByUser) {
                icon.removeClass("fa-heart-o").addClass("fa-heart fa-lg");
                button.text(" Liked"); // Update the button text to "Liked"
            } else {
                icon.removeClass("fa-heart-o").addClass("fa-heart fa-lg");
                button.text(" Like"); // Update the button text to "Like"
            }

            // Prepend the updated icon to the button text
            button.prepend(icon);

            // Update the like count
            $("#likes-count-" + postId).text(response.likesCount);
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });
}

function toggleSeeMore(postId) {
    const shortText = document.querySelector(`#caption-${postId} .caption-short`);
    const fullText = document.querySelector(`#caption-${postId} .caption-full`);
    const seeMoreLink = document.querySelector(`#caption-${postId} .see-more`);

    if (fullText.style.display === 'none') {
        fullText.style.display = 'inline';
        shortText.style.display = 'none';
        seeMoreLink.textContent = '... See Less';
    } else {
        fullText.style.display = 'none';
        shortText.style.display = 'inline';
        seeMoreLink.textContent = '... See More';
    }
}

function previewImage() {
    const file = document.getElementById('post_image').files[0];
    const preview = document.getElementById('preview');
    const imagePreviewContainer = document.getElementById('image_preview');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // Show the image
        };

        reader.readAsDataURL(file); // Read the file as a Data URL
    } else {
        preview.style.display = 'none'; // Hide the preview if no file is selected
    }
}