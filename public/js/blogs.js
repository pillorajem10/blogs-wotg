// blogs.js

document.addEventListener('DOMContentLoaded', function () {
    // Once the content is loaded, hide the loading overlay
    const loadingOverlay = document.getElementById('loading-overlay');
    
    // Add a slight delay to make the overlay visible during the loading phase
    setTimeout(function () {
        loadingOverlay.style.visibility = 'hidden';
        loadingOverlay.style.opacity = 0;
    }, 300);
    
    const images = [
        '/images/bible-reading.jpg',  
        '/images/prayer1.jpg',
        '/images/prayer.jpg',
        '/images/prayer2.jpg',
        '/images/prayer3.jpg',          
    ];

    // Function to get a random image from the array
    const getRandomImage = () => images[Math.floor(Math.random() * images.length)];

    // Set the random background image for the header-container
    const headerContainer = document.querySelector('.header-container');
    if (headerContainer) {
        headerContainer.style.backgroundImage = `url('${getRandomImage()}')`;
    }
});
