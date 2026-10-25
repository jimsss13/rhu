let isValidDocument = false;

        async function validateLucbanDocument(file) {
            const loadingSpinner = document.getElementById('loading-spinner');
            const validationMessage = document.getElementById('validation-message');
            const submitButton = document.getElementById('submit-button');

            try {
                loadingSpinner.classList.remove('hidden');
                validationMessage.innerHTML = '';
                submitButton.disabled = true;

                // Create image URL for Tesseract
                const imageUrl = URL.createObjectURL(file);

                // Perform OCR on the image
                const result = await Tesseract.recognize(imageUrl, 'eng');
                const text = result.data.text.toLowerCase();

                // Check if the text contains required keywords
                if (text.includes('lucban') && text.includes('quezon')) {
                    validationMessage.innerHTML = '<span class="text-green-500">✓ Valid Lucban, Quezon document verified</span>';
                    isValidDocument = true;
                    submitButton.disabled = false;
                } else {
                    validationMessage.innerHTML = '<span class="text-red-500">✗ Invalid document. Must be a valid Lucban, Quezon residency document</span>';
                    isValidDocument = false;
                    submitButton.disabled = true;

                    // Clear the file input and preview
                    document.getElementById('residency-file').value = '';
                    document.getElementById('default-upload-content').classList.remove('hidden');
                    document.getElementById('image-preview').classList.add('hidden');
                }
            } catch (error) {
                validationMessage.innerHTML = '<span class="text-red-500">✗ Error validating document. Please try again.</span>';
                isValidDocument = false;
                submitButton.disabled = true;
            } finally {
                loadingSpinner.classList.add('hidden');
                URL.revokeObjectURL(imageUrl);
            }
        }

        document.getElementById('residency-file').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            const defaultContent = document.getElementById('default-upload-content');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const validationMessage = document.getElementById('validation-message');

            if (file) {
                if (!file.type.startsWith('image/')) {
                    validationMessage.innerHTML = '<span class="text-red-500">✗ Please upload an image file</span>';
                    this.value = '';
                    return;
                }

                // Show image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    defaultContent.classList.add('hidden');
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);

                // Validate the document
                await validateLucbanDocument(file);
            } else {
                // Reset to default view if no file selected
                defaultContent.classList.remove('hidden');
                imagePreview.classList.add('hidden');
                previewImg.src = '';
                validationMessage.innerHTML = '';
                isValidDocument = false;
            }
        });

        // Form submission handler
        document.getElementById('registration-form').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!isValidDocument) {
                alert('Please upload a valid Lucban, Quezon residency document');
                return;
            }

            // Add your form submission logic here
            alert('Form submitted successfully!');
        });
