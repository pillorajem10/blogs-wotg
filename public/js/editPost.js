$(document).ready(function() {
    // Function to preview selected images
    function previewImages() {
        const previewContainer = $('#image-preview-container');
        previewContainer.empty(); // Clear previous previews
        
        const files = $('#posts_file_path')[0].files; // Get selected files
        if (files) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgElement = $('<img />', {
                        src: e.target.result,
                        class: 'img-thumbnail preview-img'
                    });
                    previewContainer.append(imgElement);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // Bind previewImages function to the file input change event
    $('#posts_file_path').on('change', function() {
        previewImages();  // Call the function when the user selects files
    });
});
