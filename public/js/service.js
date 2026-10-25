"https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
const serviceData = {
    laboratory: {
        title: 'Laboratory Services',
        rows: [
            { type: 'Sputum Test', fee: '₱60' },
            { type: 'Urinalysis', fee: '₱50' },
            { type: 'Fecalysis', fee: '₱50' },
            { type: 'Hemoglobin/Hematocrit', fee: '₱40' },
            { type: 'Blood Typing', fee: '₱40' },
            { type: 'Rhesus (Rh) Typing', fee: '₱30' },
            { type: 'Rapid Antigen Test', fee: '₱350' },
            { type: 'Pregnancy Test', fee: '₱100' }
        ]
    },
    vaccination: {
        title: 'Vaccination Services',
        rows: [
            { type: 'Anti-Rabis Vaccine', fee: '₱500'},
            { type: 'COVID-19 Vaccine', fee: '₱1,500' },
            { type: 'Flu Vaccine', fee: '₱800' },
            { type: 'Hepatitis B Vaccine', fee: '₱1,200' },
            { type: 'Tetanus Vaccine', fee: '₱600' }
        ]
    },
    consultation: {
        title: 'Type of Consultation Services',
        rows: [
            { type: 'General Consultation', fee: 'Free' },
            { type: 'Virtual Consultation', fee: 'Free' },
            { type: 'Referal Consultation', fee: 'Free' },
            { type: 'Follow-up Consultation', fee: 'Free' }
        ]
    },
    other: {
        title: 'Other Fees to consider',
        rows: [
            { type: 'Medical Certificate', fee: '₱100' },
            { type: 'Minor Surgeries', fee: '₱500' },
            { type: 'Dental (Bunot)', fee: '₱200' },
            { type: 'Dental Certificate', fee: '₱100' },
            { type: 'Newborn Screening', fee: '₱1,750'}
        ]
    }
};

 // Get DOM elements
 const modal = document.getElementById('serviceModal');
 const modalTitle = document.getElementById('modalTitle');
 const modalBody = document.getElementById('modalBody');
 const modalClose = document.querySelector('.modal-close');

 // Open Modal Function
 function openModal(serviceType) {
     const service = serviceData[serviceType];

     // Set title
     modalTitle.textContent = service.title;

     // Create table
     let tableHtml = `
         <table class="modal-table">
             <thead>
                 <tr>
                     <th>Type of Service</th>
                     <th>Fee</th>
                 </tr>
             </thead>
             <tbody>
                 ${service.rows.map(row => `
                     <tr>
                         <td>${row.type}</td>
                         <td>${row.fee}</td>
                     </tr>
                 `).join('')}
             </tbody>
         </table>
     `;

     modalBody.innerHTML = tableHtml;
     modal.style.display = 'flex';
 }

 // Close Modal Function
 function closeModal() {
     modal.style.display = 'none';
 }

 // Event Listeners for Services
 document.querySelector('.service-laboratory').addEventListener('click', () => openModal('laboratory'));
 document.querySelector('.service-vaccination').addEventListener('click', () => openModal('vaccination'));
 document.querySelector('.service-consultation').addEventListener('click', () => openModal('consultation'));
 document.querySelector('.service-other').addEventListener('click', () => openModal('other'));

 // Close modal when clicking close button
 modalClose.addEventListener('click', closeModal);

 // Close modal when clicking outside
 modal.addEventListener('click', (event) => {
     if (event.target === modal) {
         closeModal();
     }
 });