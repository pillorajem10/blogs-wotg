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

$(document).ready(function() {
    // Initialize Slick Carousel
    $('.carousel .card-container').slick({
        infinite: true,            // Enable infinite looping
        slidesToShow: 3,           // Number of cards to show at once
        slidesToScroll: 1,         // Number of cards to scroll at a time
        autoplay: true,            // Enable autoplay
        autoplaySpeed: 3000,       // Speed of autoplay (2 seconds)
        arrows: false,              // Show next/prev arrows
        dots: true,                // Show pagination dots
        responsive: [
            {
                breakpoint: 1024, // For medium screens (like tablets)
                settings: {
                    slidesToShow: 2, // Show 2 cards
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,  // For small screens (like phones)
                settings: {
                    slidesToShow: 2, // Show 1 card
                    slidesToScroll: 1
                }
            }
        ]
    });

    // Any other custom JavaScript code related to posts can go here.
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

function addComment(postId) {
    $.ajax({
        url: `/community/${postId}/comment`, // Use the route defined in your `web.php`
        type: 'POST',
        data: {
            _token: window.Laravel.csrfToken, // CSRF token for security
            comment_text: $(`#comment-text-${postId}`).val(), // Capture the comment text
        },
        success: function(response) {
            if (response.success) {
                const commentHTML = `
                    <div class="comment">
                        <div class="comment-avatar">
                            ${response.comment.user_profile_picture
                                ? `<img src="data:image/jpeg;base64,${response.comment.user_profile_picture}" alt="User Avatar">`
                                : `<div class="profile-circle-comment">
                                    <span>${response.comment.user_initial}</span>
                                  </div>`
                            }
                        </div>
                        <div class="comment-body">
                            <div class="comment-author">
                                <strong>${response.comment.user_fname} ${response.comment.user_lname}</strong>
                                <span class="comment-time">Just now</span>
                            </div>
                            <p class="comment-text">${response.comment.comment_text}</p>
                        </div>
                    </div>
                `;

                const commentsList = $(`#comments-list-${postId}`);
                commentsList.append(commentHTML);

                // Clear the input field after submission
                $(`#comment-text-${postId}`).val('');
            } else {
                alert('Failed to submit the comment');
            }
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