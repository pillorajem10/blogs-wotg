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

$(document).ready(function () {
    let nextPageUrl = $('#posts-container').data('next-page-url');
    const $loadingSpinner = $('#loading-spinner');
    
    // Infinite Scroll
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            if (nextPageUrl) {
                loadMorePosts();
            }
        }
    });
    
    function loadMorePosts() {
        if (!$loadingSpinner.is(':visible')) {
            $loadingSpinner.show(); // Show spinner before loading
        }
    
        $.ajax({
            url: nextPageUrl,
            type: 'get',
            beforeSend: function () {
                nextPageUrl = ''; // Temporarily disable to prevent multiple triggers
            },
            success: function (data) {
                if (data.nextPageUrl) {
                    nextPageUrl = data.nextPageUrl; // Update URL for next page
                    $('#posts-container').append(data.view); // Render fetched posts
                } else {
                    // No more pages to load
                    nextPageUrl = null;
                }
            },
            error: function (xhr, status, error) {
                console.error("Error loading posts:", error);
            },
            complete: function () {
                $loadingSpinner.hide(); // Hide spinner after AJAX completes
            }
        });
    }

    // Like button click handler with delegation
    $(document).on('click', '.like-btn', function () {
        const postId = $(this).data('post-id');
        likePost(postId);
    });

    $(document).on('click', '.comment-btn', function () {
        const postId = $(this).data('post-id');
        showCommentModal(postId);
    });
    
    // Function to show the comment modal
    function showCommentModal(postId) {
        const modal = $(`#commentModal-${postId}`);
        if (modal.length) {
            modal.css('display', 'block'); // Show the modal
        } else {
            console.error(`Modal for post ID ${postId} not found.`);
        }
    }

    function likePost(postId) {
        $.ajax({
            url: '/community/' + postId + '/like',
            type: 'POST',
            data: {
                _token: window.Laravel.csrfToken, // Use the csrfToken from the window object
            },
            success: function (response) {
                const button = $("#post-" + postId).find(".like-btn");
                const icon = button.find("i");

                if (response.likedByUser) {
                    icon.removeClass("fa-heart-o").addClass("fa-heart fa-lg");
                    button.text(" Liked"); // Update the button text to "Liked"
                } else {
                    icon.removeClass("fa-heart fa-lg").addClass("fa-heart-o");
                    button.text(" Like"); // Update the button text to "Like"
                }

                button.prepend(icon);

                // Update the like count
                $("#likes-count-" + postId).text(response.likesCount);

                // Fetch and update the likers list in the modal
                updateLikersList(postId);
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    }
});

$(document).ready(function () {
    // Close any modal when clicking the close buttons
    $(document).on('click', '.close-modal', function () {
        const modalType = $(this).data('modal-type');
        const postId = $(this).data('post-id');
        closeModal(modalType, postId);
    });
});

/**
 * Generalized function to close modals
 */
function closeModal(modalType, postId) {
    let modalId;

    // Determine the modal ID based on modal type and postId
    switch (modalType) {
        case 'likers':
            modalId = `#modal-likers-${postId}`;
            break;
        case 'comment':
            modalId = `#commentModal-${postId}`;
            break;
        default:
            console.error('Unknown modal type');
            return;
    }

    const modal = $(modalId);
    if (modal.length) {
        modal.css('display', 'none');
    } else {
        console.error(`Modal with ID ${modalId} not found.`);
    }
}



// Function to update the list of users who liked the post in real-time
function updateLikersList(postId) {
    $.ajax({
        url: '/community/' + postId + '/likers', // Ensure this route exists
        type: 'GET',
        success: function(response) {
            var likersList = $("#likers-list-" + postId);
            likersList.empty(); // Clear existing list
            
            // If there are no likers, show a message
            if (response.likers.length === 0) {
                likersList.append('<p>No one has liked this post yet.</p>');
                return;
            }

            // Loop through each liker and append to the list
            response.likers.forEach(function(liker) {
                var likerHtml = getLikerHtml(liker);
                likersList.append(likerHtml);
            });
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });
}

// Helper function to generate HTML for each liker
function getLikerHtml(liker) {
    return `
        <div class="liker">
            <div class="user-info-liker">
                <div class="comment-avatar">
                    ${liker.user_profile_picture ? 
                        `<img src="data:image/jpeg;base64,${liker.user_profile_picture}" alt="User Avatar">` : 
                        `<div class="profile-circle-comment">
                            <span>${liker.user_initial}</span>
                        </div>`
                    }
                </div>
                <span>${liker.user_fname} ${liker.user_lname}</span>
            </div>
            <div class="liker-icon">
                <i class="fa fa-heart fa-lg"></i>
            </div>
        </div>
    `;
}

function toggleReplyBox(commentId) {
    const replySection = $(`#reply-section-${commentId}`);
    
    if (replySection.is(':visible')) {
        replySection.hide();
    } else {
        replySection.show();
    }
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

function toggleReplies(commentId) {
    const repliesList = $(`#replies-list-${commentId}`);
    
    if (repliesList.css('display') === 'none') {
        repliesList.css('display', 'block');
    } else {
        repliesList.css('display', 'none');
    }
}

function addReply(commentId) {
    $.ajax({
        url: `/community/${commentId}/replies`, // Ensure this route exists
        type: 'POST',
        data: {
            _token: window.Laravel.csrfToken, // CSRF token
            reply_text: $(`#reply-text-${commentId}`).val(),
        },
        success: function(response) {
            if (response.success) {
                const replyHTML = `
                    <div class="reply">
                        <div class="reply-avatar">
                            ${
                                response.reply.user_profile_picture
                                    ? `<img src="data:image/jpeg;base64,${response.reply.user_profile_picture}" alt="User Avatar">`
                                    : `<div class="profile-circle-reply">
                                        <span>${response.reply.user_initial}</span>
                                      </div>`
                            }
                        </div>
                        <div class="reply-body">
                            <div class="reply-author">
                                <strong>${response.reply.user_fname} ${response.reply.user_lname}</strong>
                                <span class="reply-time">Just now</span>
                            </div>
                            <p class="reply-text">${response.reply.reply_text}</p>
                        </div>
                    </div>
                `;

                const repliesList = $(`#replies-list-${commentId}`);
                repliesList.append(replyHTML);

                // Clear the reply input field
                $(`#reply-text-${commentId}`).val('');

                // Increment the reply count directly in the DOM
                const replyCountSpan = $(`#reply-count-${commentId}`);
                let currentCount = parseInt(replyCountSpan.text().replace('Replies: ', ''), 10) || 0;
                replyCountSpan.text(`Replies: ${currentCount + 1}`);

                // Show replies section
                if (repliesList.css('display') === 'none') {
                    toggleReplies(commentId);
                }
            } else {
                alert('Failed to submit the reply');
            }
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });
}



// Function to open the modal and show the likers
function showLikersModal(postId) {
    document.getElementById('modal-likers-' + postId).style.display = 'block';
}

// Function to close the modal
function closeLikersModal(postId) {
    document.getElementById('modal-likers-' + postId).style.display = 'none';
}

// Close the modal if the user clicks outside of it
window.onclick = function(event) {
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        if (event.target == modal) {
            modal.style.display = 'none';
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