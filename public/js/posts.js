// posts.js

document.addEventListener('DOMContentLoaded', function () {
    // Once the content is loaded, hide the loading overlay
    const loadingOverlay = document.getElementById('loading-overlay');
    const fileInput = document.getElementById('posts_file_path');
    const previewContainer = document.getElementById('file_preview');
    
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

    if (fileInput) {
        fileInput.addEventListener('change', () => {
            previewContainer.innerHTML = ''; // Clear previous previews

            if (fileInput.files) {
                Array.from(fileInput.files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100%';
                        img.style.height = 'auto';
                        img.style.marginTop = '10px';
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    }
});

$(document).ready(function () {
    let nextPageUrl = $('#posts-container').data('next-page-url');
    const $loadingSpinner = $('#loading-spinner');
    
    // Infinite Scroll
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 60) {
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
                nextPageUrl = data.nextPageUrl; // Update the URL for the next page
                $('#posts-container').append(data.view); // Append new posts
            },
            error: function (xhr, status, error) {
                console.error("Error loading posts:", error);
            },
            complete: function () {
                $loadingSpinner.hide(); // Hide spinner after AJAX completes
            }
        });
    }

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

    /*
    $(".like-btn").on("click", function () {
        var postId = $(this).data("post-id");
        likePost(postId);
    });
    */

    // Like button click handler with delegation
    $(document).on('click', '.react-btn', function () {
        const postId = $(this).data('post-id');
        const reaction = $(this).data('reaction'); // e.g., 'like', 'heart', 'care'
        
        reactToPost(postId, reaction, $(this)); // Pass the button element as well
    });
    
    function reactToPost(postId, reaction, button) {
        $.ajax({
            url: '/community/' + postId + '/react',
            type: 'POST',
            data: {
                _token: window.Laravel.csrfToken, // CSRF token
                reaction: reaction,
            },
            success: function (response) {
                // Update the reaction counts
                $("#likes-count-" + postId).text(response.reactionCounts.like);
                $("#hearts-count-" + postId).text(response.reactionCounts.heart);
                $("#cares-count-" + postId).text(response.reactionCounts.care);
                $("#all-reactions-count-" + postId).text(response.reactionCounts.all); // Update the total count

                console.log('response.reactionCounts.all', response.reactionCounts.all)
    
                // Update the button state and text (icon)
                const icon = button.find('i');
                
                // First, remove 'active' class from all buttons for this post
                $(".react-btn[data-post-id='" + postId + "']").removeClass('active');
    
                // Check if the user has reacted and update the button style accordingly
                if (response.reactedByUser) {
                    // Mark the clicked button as active
                    button.addClass('active');
                        
                    // Update the icon based on the selected reaction
                    if (reaction === 'like') {
                        icon.removeClass("fa-thumbs-up").addClass("fa-thumbs-up fa-lg");
                    } else if (reaction === 'heart') {
                        icon.removeClass("fa-heart").addClass("fa-heart fa-lg");
                    } else if (reaction === 'care') {
                        icon.removeClass("fa-laugh").addClass("fa-laugh fa-lg");
                    }
                } else {
                    // If the user unreacted, deactivate the button and reset the icon
                    button.removeClass('active');
                        
                    // Reset the icon to the default
                    if (reaction === 'like') {
                        icon.removeClass("fa-thumbs-up").addClass("fa-thumbs-up fa-lg");
                    } else if (reaction === 'heart') {
                        icon.removeClass("fa-heart").addClass("fa-heart fa-lg");
                    } else if (reaction === 'care') {
                        icon.removeClass("fa-laugh").addClass("fa-laugh fa-lg");
                    }
                }
    
                // Dynamically update the list of likers in real-time
                updateLikersList(postId, response.userReaction); // Pass the user's current reaction
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    }
    
    
    // Function to update the likers list in the modal
    function updateLikersList(postId, userReaction) {
        $.ajax({
            url: '/community/' + postId + '/likers', // Assuming you have an endpoint for this
            type: 'GET',
            success: function (response) {
                const likersListContainer = $("#likers-list-" + postId);
                likersListContainer.empty(); // Clear existing list
    
                // Loop through each liker and display in the modal
                response.likers.forEach(liker => {
                    const userAvatar = liker.user_profile_picture
                        ? `<img src="data:image/jpeg;base64,${liker.user_profile_picture}" alt="User Avatar">`
                        : `<div class="profile-circle-comment">
                                <span>${liker.user_initial}</span>
                            </div>`;
    
                    const reactionIcon = liker.reaction === 'like'
                        ? `<i class="fa fa-thumbs-up fa-lg"></i>`
                        : liker.reaction === 'heart'
                        ? `<i class="fa fa-heart fa-lg"></i>`
                        : `<i class="fa fa-laugh fa-lg"></i>`;
    
                    const likerElement = `
                        <div class="liker" data-reaction="${liker.reaction}">
                            <div class="user-info-liker">
                                <div class="comment-avatar">${userAvatar}</div>
                                <span>${liker.user_fname} ${liker.user_lname}</span>
                            </div>
                            <div>${reactionIcon}</div>
                        </div>
                    `;
    
                    likersListContainer.append(likerElement);
                });
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
        url: `/community/${postId}/comment`, 
        type: 'POST',
        data: {
            _token: window.Laravel.csrfToken,
            comment_text: $(`#comment-text-${postId}`).val(),
        },
        success: function(response) {
            if (response.success) {
                const commentHTML = `
                    <div class="comment" data-comment-id="${response.comment.id}">
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
                            
                            <!-- Reply Count & View Replies Button -->
                            <div class="comment-reply-section">
                                <span class="reply-count" id="reply-count-${response.comment.id}">Replies: 0</span>
                                <button class="btn-view-replies mt-2" onclick="toggleReplies(${response.comment.id})">
                                    View Replies
                                </button>
                            </div>                                                
                
                            <!-- Reply Button -->
                            <button class="btn-reply mt-2" onclick="toggleReplyBox(${response.comment.id})">
                                <i class="fa fa-comment fa-lg"></i>
                                <span>Reply</span>
                            </button>
                
                            <!-- Delete Button (Optional: Only for comment owner) -->
                            ${window.Laravel.authUserId === response.comment.user_id
                                ? `<button onclick="deleteComment(${response.comment.id}, ${response.comment.post_id})" class="delete-btn mt-2">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        <span>Delete</span>
                                   </button>`
                                : ''
                            }                            
                
                            <!-- Reply Input Section -->
                            <div class="reply-section" id="reply-section-${response.comment.id}" style="display: none;">
                                <textarea id="reply-text-${response.comment.id}" class="form-control" placeholder="Write a reply..." rows="2"></textarea>
                                <button class="btn-submit-reply mt-1" data-comment-id="${response.comment.id}">Submit Reply</button>
                            </div>
                
                            <!-- Display Existing Replies -->
                            <div class="replies-list" id="replies-list-${response.comment.id}"></div>
                        </div>
                    </div>
                `;            
            
                const commentsList = $(`#comments-list-${response.post_id}`);
                commentsList.append(commentHTML);

                // Clear the comment text box after submission
                $(`#comment-text-${response.post_id}`).val('');
            } else {
                alert('Failed to submit the comment');
            }
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });
}


function deleteComment(commentId, postId) {
    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }

    $.ajax({
        url: `/community/${commentId}/comment`, // The URL of your delete route
        type: 'DELETE',
        data: {
            _token: window.Laravel.csrfToken, // Include the CSRF token
        },
        success: function(response) {
            if (response.success) {
                // Remove the comment from the DOM
                $(`.comment[data-comment-id="${commentId}"]`).remove();

                // Update the comment count (subtract 1)
                const commentsCountElement = $(`#comments-count-${postId}`);
                let currentCount = parseInt(commentsCountElement.text());
                commentsCountElement.text(currentCount - 1);
            } else {
                alert('Failed to delete the comment');
            }
        },
        error: function(error) {
            console.error('Error:', error);
            alert('An error occurred while trying to delete the comment');
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

$(document).on('click', '.btn-submit-reply', function() {
    const commentId = $(this).data('comment-id'); // Dynamically fetch the commentId
    const replyText = $(`#reply-text-${commentId}`).val();

    if (!replyText) {
        alert('Reply text cannot be empty');
        return;
    }

    $.ajax({
        url: `/community/${commentId}/replies`, 
        type: 'POST',
        data: {
            _token: window.Laravel.csrfToken,
            reply_text: replyText,
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
});

// Function to open the modal and show the likers
function showLikersModal(postId) {
    document.getElementById('modal-likers-' + postId).style.display = 'block';
}

function filterReactions(postId, reactionType) {
    const likersList = document.getElementById('likers-list-' + postId);
    const likers = likersList.getElementsByClassName('liker');

    for (let i = 0; i < likers.length; i++) {
        const liker = likers[i];
        const reaction = liker.getAttribute('data-reaction');

        // Show all reactions if 'all' is selected, otherwise filter by specific reaction type
        if (reactionType === 'all' || reaction === reactionType) {
            liker.style.display = 'flex'; // Show the liker
        } else {
            liker.style.display = 'none'; // Hide the liker
        }
    }
}

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