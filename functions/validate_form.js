// Sélection des éléments du formulaire
const form = document.querySelector('form');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm_password');
const emailInput = document.getElementById('email');

// Écouteur d'événement pour la validation des champs lorsqu'ils perdent le focus
usernameInput.addEventListener('blur', validateUsername);
passwordInput.addEventListener('blur', validatePassword);
if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
}
emailInput.addEventListener('blur', validateEmail);

// Écouteur d'événement pour la validation du formulaire avant la soumission
form.addEventListener('submit', validateForm);

// Fonction de validation du champ "Nom d'utilisateur"
function validateUsername() {
    const value = usernameInput.value.trim();
    if (value === '') {
        showError(usernameInput, 'Le nom d\'utilisateur est requis');
    } else if (value.length < 6) {
        showError(usernameInput, 'Le nom d\'utilisateur doit contenir au moins 6 caractères et faire au maximum 20 caractères.');
    } else {
        showSuccess(usernameInput);
    }
}

// Fonction de validation du champ "Mot de passe"
function validatePassword() {
    const value = passwordInput.value.trim();
    if (value === '') {
        showError(passwordInput, 'Le mot de passe est requis');
    } else if (value.length < 8) {
        showError(passwordInput, 'Le mot de passe doit contenir au moins 8 caractères et faire au maximum 20 caractères.');
    } else {
        showSuccess(passwordInput);
    }
}

// Fonction de validation du champ "Confirmer le mot de passe"
function validateConfirmPassword() {
    const passwordValue = passwordInput.value.trim();
    const confirmPasswordValue = confirmPasswordInput.value.trim();
    if (confirmPasswordValue === '') {
        showError(confirmPasswordInput, 'Veuillez confirmer votre mot de passe');
        confirmPasswordInput.classList.remove('input_correct');
        confirmPasswordInput.classList.add('input_error');
    } else if (confirmPasswordValue !== passwordValue) {
        showError(confirmPasswordInput, 'Les mots de passe ne correspondent pas');
        confirmPasswordInput.classList.remove('input_correct');
        confirmPasswordInput.classList.add('input_error');
        passwordInput.classList.remove('input_correct');
        passwordInput.classList.add('input_error');
    } else {
        showSuccess(confirmPasswordInput);
        confirmPasswordInput.classList.remove('input_error');
        confirmPasswordInput.classList.add('input_correct');
        showSuccess(passwordInput);
        passwordInput.classList.remove('input_error');
        passwordInput.classList.add('input_correct');
    }
}





// Fonction de validation du champ "Email"
function validateEmail() {
    const value = emailInput.value.trim();
    if (value === '') {
        showError(emailInput, 'L\'email est requis');
    } else if (!isValidEmail(value)) {
        showError(emailInput, 'L\'email n\'est pas valide');
    } else if (value.length > 50) {
        showError(emailInput, 'L\'email doit avoir au maximum 50 caractères');
    } else {
        showSuccess(emailInput);
    }
}


// Fonction de validation du formulaire
function validateForm(e) {
    e.preventDefault();

    validateUsername();
    validatePassword();
    validateConfirmPassword();
    validateEmail();

    // Si tous les champs sont valides, soumettre le formulaire
    if (form.querySelectorAll('.input_error').length === 0) {
        form.submit();
    }
}


// Fonction pour afficher une erreur
function showError(input, message) {
    input.classList.remove('input_correct');
    input.classList.add('input_error');
    const errorText = input.parentElement.querySelector('.error_message');
    if (errorText) {
        // Vérifier si une erreur similaire est déjà affichée
        if (errorText.innerText !== message) {
            errorText.innerText = message;
        }
    } else {
        const newErrorText = document.createElement('div');
        newErrorText.classList.add('error_message');
        newErrorText.innerText = message;
        const inputGroup = input.parentElement;
        inputGroup.appendChild(newErrorText);
    }
}

// Fonction pour afficher un champ valide
function showSuccess(input) {
    input.classList.remove('input_error');
    input.classList.add('input_correct');
    const errorText = input.parentElement.querySelector('.error_message');
    if (errorText) {
        errorText.remove();
    }
}

// Fonction pour vérifier si l'e-mail est valide
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
