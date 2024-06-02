document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordField = document.getElementById('cpassword');

    const handleMouseDown = (passwordField, toggleIcon) => {
        return function() {
            passwordField.setAttribute('type', 'text');
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        };
    };

    const handleMouseUp = (passwordField, toggleIcon) => {
        return function() {
            passwordField.setAttribute('type', 'password');
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        };
    };

    const handleMouseOut = (passwordField, toggleIcon) => {
        return function() {
            passwordField.setAttribute('type', 'password');
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        };
    };

    togglePassword.addEventListener('mousedown', handleMouseDown(passwordField, togglePassword));
    togglePassword.addEventListener('mouseup', handleMouseUp(passwordField, togglePassword));
    togglePassword.addEventListener('mouseout', handleMouseOut(passwordField, togglePassword));

    toggleConfirmPassword.addEventListener('mousedown', handleMouseDown(confirmPasswordField, toggleConfirmPassword));
    toggleConfirmPassword.addEventListener('mouseup', handleMouseUp(confirmPasswordField, toggleConfirmPassword));
    toggleConfirmPassword.addEventListener('mouseout', handleMouseOut(confirmPasswordField, toggleConfirmPassword));
});