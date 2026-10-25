"https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        // Get modal elements
        const modal = document.getElementById('teamModal');
        const closeModal = document.querySelector('.close-modal');
        const modalName = document.getElementById('modalName');
        const modalPosition = document.getElementById('modalPosition');
        const modalWorkPosition = document.getElementById('modalWorkPosition');
        const modalLocation = document.getElementById('modalLocation');
        const modalSchedule = document.getElementById('modalSchedule');

        // Add click event to team container (event delegation)
        document.getElementById('teamContainer').addEventListener('click', function(event) {
            // Find the closest team block
            const teamBlock = event.target.closest('.team-block');
            
            if (teamBlock) {
                // Get data attributes
                const name = teamBlock.getAttribute('data-name');
                const position = teamBlock.getAttribute('data-position');
                const workPosition = teamBlock.getAttribute('data-work-position');
                const location = teamBlock.getAttribute('data-location');
                const schedule = teamBlock.getAttribute('data-schedule');
                
                // Update modal content
                modalName.textContent = name;
                modalPosition.textContent = position;
                modalWorkPosition.textContent = workPosition;
                modalLocation.textContent = location;
                modalSchedule.textContent = schedule;

                // Show the modal
                modal.style.display = 'block';
            }
        });

        // Close modal when clicking 'x'
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Close modal when clicking outside the modal
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });