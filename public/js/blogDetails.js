// Add event listener for the copy link button
document.querySelector('.copy-link').addEventListener('click', function() {
    var url = this.getAttribute('data-url');  // Get the URL from the data-url attribute
    copyLink(url);  // Call the copyLink function with the URL
});

function copyLink(url) {
    // Create a temporary input element to hold the URL
    var tempInput = document.createElement('input');
    tempInput.value = url;
    document.body.appendChild(tempInput);

    // Select the text and copy it to the clipboard
    tempInput.select();
    document.execCommand('copy');

    // Remove the temporary input element
    document.body.removeChild(tempInput);

    // Optionally, show a message or alert to notify the user
    alert('Link copied to clipboard!');
}
