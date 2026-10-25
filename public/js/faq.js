document.addEventListener('DOMContentLoaded', function () {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        item.addEventListener('click', function () {
            // Close all other FAQ items
            faqItems.forEach(otherItem => {
                if (otherItem !== this) {
                    otherItem.classList.remove('active');
                }
            });

            // Toggle the clicked FAQ item
            this.classList.toggle('active');
        });
    });
});
