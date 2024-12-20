// Add event listener for the copy link button
document.querySelector('.copy-link').addEventListener('click', function() {
    var url = this.getAttribute('data-url');  // Get the URL from the data-url attribute
    copyLink(url);  // Call the copyLink function with the URL
});

// Function to copy the link to the clipboard
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

// Add event listener for the copy all button
// Add event listener for the copy all button
document.querySelector('.copy-all').addEventListener('click', function() {
    var blogContent = document.querySelector('.body-text');  // Get the HTML content of the blog body
    var releaseDate = document.querySelector('.release-date'); // Get the release date element
    var seeMoreUrl = "https://blogs.wotgonline.com/blogs";  // URL for "See More Blogs"
    
    var formattedContent = formatContentForCopy(blogContent, releaseDate, seeMoreUrl);  // Format content
    copyBlogContent(formattedContent);  // Call the copyBlogContent function with the formatted text
});

// Function to format the HTML content into plain text while adding release date and the actual URL
function formatContentForCopy(content, releaseDate, seeMoreUrl) {
    // Create a clone of the content to avoid modifying the original
    var clone = content.cloneNode(true);

    // Convert <p> and <br> tags to newlines
    clone.querySelectorAll('p').forEach(function(p) {
        p.innerHTML = p.innerHTML.replace(/<br\s*\/?>/g, '\n');  // Convert <br> tags to newline characters
    });

    // Get the plain text content
    var blogText = clone.innerText || clone.textContent;

    // Prepend the release date and append the "See More Blogs" URL
    return `${releaseDate.innerText}\n\n${blogText}\n\nSee More Blogs: ${seeMoreUrl}`;
}

// Function to copy the formatted content to the clipboard
function copyBlogContent(content) {
    // Create a temporary textarea element to hold the plain text
    var tempTextarea = document.createElement('textarea');
    tempTextarea.value = content; // Use the formatted plain text
    document.body.appendChild(tempTextarea);

    // Select the text and copy it to the clipboard
    tempTextarea.select();
    document.execCommand('copy');

    // Remove the temporary textarea element
    document.body.removeChild(tempTextarea);

    // Optionally, show a message or alert to notify the user
    alert('Blog content copied to clipboard!');
}


