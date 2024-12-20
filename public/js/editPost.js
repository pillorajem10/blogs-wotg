$(document).ready(function() {
    // Function to preview selected images and videos
    function previewMedia() {
        const previewContainer = $('#image-preview-container');
        previewContainer.empty(); // Clear previous previews
        
        const files = $('#posts_file_path')[0].files; // Get selected files
        if (files) {
            Array.from(files).forEach(file => {
                const fileExtension = file.name.split('.').pop().toLowerCase();
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileUrl = e.target.result;
                    if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileExtension)) {
                        // If it's an image
                        const imgElement = $('<img />', {
                            src: fileUrl,
                            class: 'img-thumbnail preview-img',
                            css: {
                                maxWidth: '100%',  // Apply max-width style directly to the video element
                            }
                        });
                        previewContainer.append(imgElement);
                    } else if (['mp4', 'webm', 'ogg', 'avi', 'mov', 'mkv'].includes(fileExtension)) {
                        // If it's a video
                        const videoElement = $('<video />', {
                            class: 'img-thumbnail preview-img',
                            controls: true,
                            css: {
                                maxWidth: '200px',  // Apply max-width style directly to the video element
                            }
                        });
                        videoElement.append($('<source />', {
                            src: fileUrl,
                            type: `video/${fileExtension}`
                        }));
                        previewContainer.append(videoElement);
                    }
                };
                reader.readAsDataURL(file);
            });
        }        
    }

    // Bind previewMedia function to the file input change event
    $('#posts_file_path').on('change', function() {
        previewMedia();  // Call the function when the user selects files
    });
});
