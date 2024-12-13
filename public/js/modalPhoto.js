// Store the images and current index for each post
let imageSets = {};
let currentIndex = 0;

// Open the modal and initialize the images
function openModal(postId, index, allImages) {
    console.log('Opening modal for post:', postId);

    // Store all images for the post
    imageSets[postId] = allImages;

    // Set the current index
    currentIndex = index;

    // Get the modal and modal image
    const modal = document.getElementById('photoModal');
    const modalImage = document.getElementById('modalImage');

    if (!modal || !modalImage) {
        console.error('Modal or modal image element not found!');
        return;
    }

    // Set the modal image and display it
    modalImage.src = imageSets[postId][currentIndex];
    modal.style.display = 'flex';
}

// Close the modal
function closeModal() {
    const modal = document.getElementById('photoModal');
    if (modal) {
        modal.style.display = 'none';
    }
    // Optionally clear the images for the current post if needed
    const currentPostId = Object.keys(imageSets)[0];
    if (currentPostId) {
        delete imageSets[currentPostId];
    }
}

// Navigate to the next or previous image
function changeImage(direction) {
    const modalImage = document.getElementById('modalImage');
    if (!modalImage || !imageSets) return;

    // Update the index within bounds
    currentIndex = (currentIndex + direction + imageSets[Object.keys(imageSets)[0]].length) % imageSets[Object.keys(imageSets)[0]].length;

    // Update the image source
    modalImage.src = imageSets[Object.keys(imageSets)[0]][currentIndex];
}
