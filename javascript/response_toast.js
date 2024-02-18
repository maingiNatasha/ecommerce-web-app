//Function to handle the response from the server
function handleResponse(response) {
    var responseParts = response.split('|');

    if (responseParts.length === 2) {
        var message = responseParts[0];
        var type = responseParts[1];

        showToast(message, type);
    }
}

// Function to display toast
function showToast(message, type) {
    // Get a reference to the toast container
    const toast = document.getElementById('toast-alert');

    // Update the content of the toast body
    const toastBody = toast.querySelector('.toast-body');
    toastBody.textContent = message;

    // Remove previous toast classes
    toast.classList.remove('text-bg-danger');

    if (type === 'success') {
        // Set the background color dynamically
        toast.style.backgroundColor = '#769FCD';

        // Add div with a specified class
        const divContainer = document.createElement('div');
        divContainer.className = 'mt-2 pt-2';
        toastBody.appendChild(divContainer);

        var btnText = 'empty';

        //Set the appropriate button text
        if (message.includes('cart')) {
            btnText = 'View My cart';
        }
        else if(message.includes('wishlist')) {
            btnText = 'View My wishlist';
        }

        // Add View Cart/ View Wishlist button
        const viewBtn = document.createElement('button');
        viewBtn.textContent = btnText;
        viewBtn.className = 'btn btn-dark btn-sm ms-2';
        viewBtn.addEventListener('click', function () {
            handleViewButtonClick(btnText);
        });

        divContainer.appendChild(viewBtn);

    }
    else if (type === 'error') {
        toast.classList.add('text-bg-danger');
    }

    // Initialize the Bootstrap toast component and show it
    const toastInstance = new bootstrap.Toast(toast);
    toastInstance.show();
}

// Handle Toast button click
function handleViewButtonClick(btnText) {
    if (btnText.includes('cart')) {
        //Redirect user to cart page
        window.location.href = 'cart.html';
    }
    else if (btnText.includes('wishlist')) {
        //Redirect user to wishlist page
        window.location.href = 'wishlist.html';
    }
}
