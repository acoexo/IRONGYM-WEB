function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.querySelector('.toggle-password-button img');

    if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    toggleButton.src = '../../src/img/invisible2.png';
    toggleButton.alt = 'Ocultar contraseña';
    } else {
    passwordInput.type = 'password';
    toggleButton.src = '../../src/img/ojo2.png';
    toggleButton.alt = 'Mostrar contraseña';
    }
}
