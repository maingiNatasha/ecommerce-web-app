// Function to handle pagination
function handlePagination(items) {
    // Select the pagination container
    const paginationContainer = document.querySelector('.pagination');

    // Select prev and next buttons
    const prevBtn = document.getElementById("previous-button");
    const nextBtn = document.getElementById("next-button");

    console.log("container count", items.length);

    // Number of cards per page
    const paginationLimit = 9;

    // Calculate total pages needed based on number of cards
    let currentPage = 1;
    const totalPages = Math.ceil(items.length / paginationLimit);
    console.log("Total Pages:", totalPages);

    // Clear existing page numbers before adding new ones
    clearPageNumbers(paginationContainer);

    // Generate page numbers then create <li> elements with page number and insert them in the pagination container
    for (let i = 1; i <= totalPages; i++) {
        const pageItem = document.createElement('li');
        pageItem.classList.add('page-item');
        pageItem.classList.add('page-number');

        const pageLink = document.createElement('a');
        pageLink.classList.add('page-link');
        pageLink.href = '#';
        pageLink.textContent = i

        if(i === currentPage) {
            pageItem.classList.add('active');
        }

        pageItem.appendChild(pageLink);

        // Insert page numbers before next button which is the last child in our pagination container
        paginationContainer.insertBefore(pageItem, paginationContainer.lastElementChild);

    }

    // Initially display the first page of cards
    updatePage(currentPage, paginationLimit, items, prevBtn, nextBtn, totalPages);

    // Handle page number clicks
    const pageNumbers = paginationContainer.querySelectorAll('.page-number');

    pageNumbers.forEach((page, index) => {
        page.addEventListener('click', () => {
            currentPage = index + 1;
            console.log("Current page:", currentPage);
            updatePage(currentPage, paginationLimit, items, prevBtn, nextBtn, totalPages);

            // Make the clicked page active
            pageNumbers.forEach((pageNumber) => {
                pageNumber.classList.remove('active');
            });

            page.classList.add('active');

        });
    });

    // Handle button clicks
    // Add listeners to prev and next buttons
    prevBtn.addEventListener('click', () => {
        if(currentPage > 1) {
            // Remove active from previously active page
            pageNumbers[currentPage - 1].classList.remove('active');

            currentPage -- ;
            updatePage(currentPage, paginationLimit, items, prevBtn, nextBtn, totalPages);

            // Add active to currently active page
            pageNumbers[currentPage - 1].classList.add('active');

            console.log("Previous button clicked. Current Page: " + currentPage);

        }
    });

    nextBtn.addEventListener('click', () => {
        if(currentPage < totalPages) {
            // Remove active from previously active page
            pageNumbers[currentPage - 1].classList.remove('active');

            currentPage ++ ;
            updatePage(currentPage, paginationLimit, items, prevBtn, nextBtn, totalPages);

            // Add active to currently active page
            pageNumbers[currentPage - 1].classList.add('active');

            console.log("Next button clicked. Current Page: " + currentPage);

        }
    });

}

// Function to clear already existing page numbers
function clearPageNumbers(paginationContainer) {
    const pageNumbers = paginationContainer.querySelectorAll('.page-number');
    pageNumbers.forEach((pageNumber) => {
        paginationContainer.removeChild(pageNumber);
    });
}

// Function to update the displayed cards on the current page
function updatePage(currentPage, paginationLimit, items, prevBtn, nextBtn, totalPages) {
    const startIndex = (currentPage - 1) * paginationLimit;
    console.log("Start Index: ", startIndex);
    const endIndex = startIndex + paginationLimit;
    console.log("End index: ", endIndex);

    items.forEach((item, index) => {
        if (index >= startIndex && index < endIndex) {
            item.style.display = 'block';
        }
        else {
            item.style.display = 'none';
        }
    });

    // Update Previous and Next button states
    updateButtonStatus(currentPage, prevBtn ,nextBtn, totalPages);
}

// Function to update the status of next and previous buttons
function updateButtonStatus(currentPage, prevBtn, nextBtn, totalPages) {
    if (currentPage === 1) {
        console.log("prev If being executed");
        prevBtn.classList.add('disabled');
    } else {
        console.log("prev Else being executed");
        prevBtn.classList.remove('disabled');
    }

    if(currentPage === totalPages) {
        console.log("next If being executed");
        nextBtn.classList.add('disabled');
    }
    else {
        console.log("next Else being executed");
        nextBtn.classList.remove('disabled');
    }
}
