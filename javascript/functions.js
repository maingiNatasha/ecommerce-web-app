// Functions from checkout.html

// Function to add tab parameter to url to store tab id
function updateUrl(tabId) {
    const url = new URL(window.location.href);

    // Set tab parameter to tabId[1:] removing "#"
    url.searchParams.set('tab', tabId.substring(1));
    window.history.pushState({}, '', url);
}

// Function to retrieve tab id from url and reload the page with active tab as current tab
function retrieveTabFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    // Retrieve tab parameter from url and assign it to active tab if present, else, assign order summary as the active tab
    const activeTab = urlParams.get('tab') || "order-summary";

    if (activeTab) {
        const tab = document.querySelector(`#${activeTab}-tab`);

        if (tab) {
            const tabPanes = document.querySelectorAll('.tab-pane');
            tabPanes.forEach((tabPane) => {
                tabPane.classList.remove('active');
            });

            // Set the tab-pane of the active tab as active
            const targetPane = document.querySelector(`#${activeTab}`);
            targetPane.classList.add('active');

            const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabs.forEach((tab) => {
                tab.classList.remove('active-tab');
            });

            // Set active-tab as active by adding the active-tab class
            tab.classList.add('active-tab');
            // Switch to tab
            tab.click();
        }
    }
}


function retrieveTabInfo(targetTabId) {
    const confirmOrderInfo = document.getElementById("confirm-order-info");

    // Clear any previous content in the Confirm Order tab
      //confirmOrderInfo.innerHTML = "";

    if(targetTabId === "#customer-info") {
        const saveBtn = document.getElementById("save-info-btn");

        saveBtn.addEventListener("click", function(event) {
            // Prevent form submission
            event.preventDefault();
            console.log(validateInputs());

            // If validateInputs === true, retrieve the information
            if(validateInputs()) {
                // Retrieve Customer Info
                let firstName = document.getElementById("first-name").value;
                let secondName = document.getElementById("second-name").value;
                let email = document.getElementById("email").value;
                let phoneNumber = document.getElementById("phone-number").value;
                let additionalNumber = document.getElementById("other-phone-number").value;
                let homeAddress = document.getElementById("home-address").value;
                let additionalAddressInfo = document.getElementById("additional-address-info").value;
                let region = document.getElementById("region").value;
                let city = document.getElementById("city").value;

                // Save in local storage
                localStorage.setItem('customerInfo', JSON.stringify({firstName, secondName, email, phoneNumber, additionalNumber, homeAddress, additionalAddressInfo, region, city}));

                // Display info in the confirm order tab
                let customerInfo = `<h4><b>Customer Information</b></h4>
                                <p>
                                    <span><b>Name: </b></span><span id="name">${firstName} ${secondName}</span><br>
                                    <span><b>Email: </b></span><span id="email">${email}</span><br>
                                    <span><b>Phone number: </b></span><span id="phone-number">${phoneNumber}</span><br>
                                    <span><b>Additional phone number: </b></span><span id="additional-number">${additionalNumber}</span><br>
                                    <span><b>Home Address: </b></span><span id="home-address">${homeAddress}</span><br>
                                    <span><b>Additional address information: </b></span><span id="additional-address-info">${additionalAddressInfo}</span><br>
                                    <span><b>Region/county: </b></span><span id="region">${region}</span><br>
                                    <span><b>City: </b></span><span id="city">${city}</span><br>
                                </p>`;

                confirmOrderInfo.innerHTML = customerInfo;

                nextTab();
            }

        });
    }

    else if (targetTabId === "#delivery") {
        const saveBtn = document.getElementById("save-delivery-option-btn");

        saveBtn.addEventListener("click", function(event) {
            event.preventDefault();

            let tabId = targetTabId.replace("#", "");
            console.log(validateRadioInputs(tabId));

            if(validateRadioInputs(tabId)) {
                const doorDeliveryRadio = document.getElementById("door-delivery");
                const pickupDeliveryRadio = document.getElementById("pick-up-delivery");

                let deliveryOption;

                if(doorDeliveryRadio.checked){
                    deliveryOption = doorDeliveryRadio.value;
                }
                else if(pickupDeliveryRadio.checked){
                    deliveryOption = pickupDeliveryRadio.value;
                }

                // Save in local storage
                localStorage.setItem('deliveryOption', JSON.stringify({deliveryOption}));

                deliveryInfo = `<h4><b>Delivery Option</b></h4>
                                <p>
                                    <span><b>Preferred delivery option: </b></span><span id="delivery-option">${deliveryOption}</span><br>
                                    <span><b>Delivery Fee: </b></span><span id="delivery-fee">n/a</span>
                                </p>`;

                confirmOrderInfo.innerHTML += deliveryInfo;

                nextTab();

            }
        });
    }

    else if (targetTabId === "#payment") {
        const saveBtn = document.getElementById("save-payment-option-btn");

        saveBtn.addEventListener("click", function(event) {
            event.preventDefault();

            let tabId = targetTabId.replace("#", "");
            console.log(validateRadioInputs(tabId));

            if(validateRadioInputs(tabId)) {
                const payNowRadio = document.getElementById("pay-now");
                const payOnDeliveryRadio = document.getElementById("pay-on-delivery");

                let paymentOption;

                if(payNowRadio.checked){
                    paymentOption = payNowRadio.value;
                }
                else if(payOnDeliveryRadio.checked){
                    paymentOption = payOnDeliveryRadio.value;
                }

                // Save in local storage
                localStorage.setItem('paymentMethod', JSON.stringify({paymentOption}));

                paymentInfo = `<h4><b>Payment Method</b></h4>
                                <p>
                                    <span><b>Preferred payment method: </b></span><span id="payment-method">${paymentOption}</span>
                                </p>`;

                confirmOrderInfo.innerHTML += paymentInfo;

                nextTab();
            }
        });
    }
}


// Function to navigate to the next tab
function nextTab() {
    const tabSequence = {
        "customer-info-tab": "delivery-tab",
        "delivery-tab": "payment-tab",
        "payment-tab": "confirm-order-tab",
        "confirm-order-tab": null
    };

    const currentTabId = document.querySelector('[data-bs-toggle="tab"].active-tab').id;
    console.log("Current tab: ", currentTabId);
    const nextTabId = tabSequence[currentTabId];
    console.log("Next tab: ", nextTabId);

    if (nextTabId) {
        let nextTab = document.getElementById(nextTabId);

        if (nextTab) {
            // Enable tab
            nextTab.classList.remove('disabled');
            // Switch to next tab
            nextTab.click();
            //updateUrl(nextTab.getAttribute('href'));
        }
    }
}