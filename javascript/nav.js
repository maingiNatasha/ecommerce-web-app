// Function to set active page
function setActivePage() {
    // Get the current URL
    const currentURL = window.location.pathname;
    //console.log('Current URL:', currentURL); // Log the current URL

    // Get all the navigation links
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    // Loop through the links
    navLinks.forEach((link) => {
        const linkURL = link.getAttribute('href').replace('..', '');
        //console.log('Link URL:', linkURL); // Log the link's URL

        // Check if the current URL includes the link's URL
        if (currentURL.includes(linkURL)) {
            link.classList.add('active'); // Add the "active" class to the matching link
        } else {
            link.classList.remove('active'); // Remove the "active" class from non-matching links
        }
    });
}

window.addEventListener('load', () => {
    // Fetch the navbar content and set the active page when the page loads
    const nav = document.querySelector('.navbar');
    fetch('../html_pages/buyer_navbar.html')
        .then((res) => res.text())
        .then((data) => {
            nav.innerHTML = data;
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            eval(doc.querySelector('script').textContent);

            // Call setActivePage after loading the navbar content
            setActivePage();

            // Make an AJAX request to fetch the username
            $.ajax({
                url: '../php_scripts/get_username.php', // Replace with the correct URL of your PHP script
                method: 'GET', // Use the appropriate HTTP method (GET or POST)
                dataType: 'json', // Specify the expected data type (json, text, etc.)
                success: function (response) {
                    // Update the username placeholder with the fetched username
                    $('#usernamePlaceholder').text(response.username);
                },
                error: function (xhr, status, error) {
                    // Handle any errors that occur during the AJAX request
                    console.error('Error:', error);
                }
            });
        })
        .catch((error) => {
            console.error('Error fetching navbar content:', error);
        });
});
