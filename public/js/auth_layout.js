document.addEventListener('DOMContentLoaded', function () {
    // Hanapin lahat ng toggle icons
    const toggleIcons = document.querySelectorAll('.icon-toggle');

    toggleIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            // 'this' ay ang icon (ang <i> tag)
            // Hanapin ang input field sa loob ng parehong parent (.form-group-icon)
            const input = this.closest('.form-group-icon').querySelector('input');

            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            } else {
                input.type = 'password';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            }
        });
    });
});