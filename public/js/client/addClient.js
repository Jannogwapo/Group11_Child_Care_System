document.addEventListener('DOMContentLoaded', function() {
    var dateInput = document.getElementById('admissionDate');
    var datePlaceholder = document.querySelector('.date-placeholder');

    dateInput.addEventListener('change', function() {
        if (dateInput.value) {
            datePlaceholder.style.display = 'none';
        } else {
            datePlaceholder.style.display = 'block';
        }
    });

    dateInput.addEventListener('focus', function() {
        datePlaceholder.style.display = 'none';
    });

    dateInput.addEventListener('blur', function() {
        if (!dateInput.value) {
            datePlaceholder.style.display = 'block';
        }
    });
});