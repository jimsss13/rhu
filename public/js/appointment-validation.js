document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.appoinment-form');
    const appointmentButton = document.querySelector('.btn-main');
    const errorMessageContainer = document.createElement('div');
    errorMessageContainer.id = 'error-message';
    errorMessageContainer.className = 'text-red-500 text-center mb-3';
    
    // All required form fields
    const requiredFields = [
        'exampleFormControlSelect1', // Department
        'exampleFormControlSelect2', // Time
        'date', // Date
        document.querySelectorAll('select[id="exampleFormControlSelect2"]')[1], // Barangay
        'name', // Full Name
        'phone', // Phone Number
        'message' // Message
    ];

    function validateForm() {
        // Reset error state
        appointmentButton.disabled = true;
        errorMessageContainer.textContent = '';
        
        // Check if all required fields are filled
        const allFieldsFilled = requiredFields.every(field => {
            const element = typeof field === 'string' 
                ? document.getElementById(field) 
                : field;
            
            if (element.tagName === 'SELECT') {
                return element.selectedIndex > 0;
            }
            
            return element.value.trim() !== '';
        });

        // Update button state and error message
        if (!allFieldsFilled) {
            appointmentButton.disabled = true;
            errorMessageContainer.textContent = "Make sure to complete the booking information";
            form.insertBefore(errorMessageContainer, appointmentButton);
            return false;
        } else {
            // Remove error message if exists
            if (errorMessageContainer.parentNode) {
                errorMessageContainer.remove();
            }
            appointmentButton.disabled = false;
            return true;
        }
    }

    // Add event listeners to all fields for real-time validation
    requiredFields.forEach(field => {
        const element = typeof field === 'string' 
            ? document.getElementById(field) 
            : field;
        
        element.addEventListener('input', validateForm);
        element.addEventListener('change', validateForm);
    });

    // Initial validation
    validateForm();

    // Handle button click
    appointmentButton.addEventListener('click', function(e) {
        if (validateForm()) {
            // Proceed to confirmation page
            window.location.href = 'confirmation.html';
        } else {
            e.preventDefault();
        }
    });

    // Initially disable the button
    appointmentButton.disabled = true;
});