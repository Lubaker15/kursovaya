class AuthValidation {
    constructor() {
        this.init();
    }

    init() {
        this.setupTabs();
        this.setupValidation();
        this.setupRealTimeValidation();
    }

    setupTabs() {
        const tabs = document.querySelectorAll(".auth__tab");
        const forms = document.querySelectorAll(".auth__form");

        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                tabs.forEach((t) => t.classList.remove("auth__tab--active"));
                forms.forEach((f) => f.classList.remove("auth__form--active"));
                tab.classList.add("auth__tab--active");
                document.getElementById(`${tab.dataset.tab}-form`).classList.add("auth__form--active");
                this.clearAllErrors();
            });
        });
    }

    setupValidation() {
        document.getElementById("register-form").addEventListener("submit", (e) => {
            if (!this.validateRegisterForm()) {
                e.preventDefault();
            }
            // если валидация пройдена – ничего не делаем, форма отправится стандартно
        });

        document.getElementById("login-form").addEventListener("submit", (e) => {
            if (!this.validateLoginForm()) {
                e.preventDefault();
            }
        });
    }

    setupRealTimeValidation() {
        document.getElementById("register-login").addEventListener("blur", () => {
            this.validateLoginField();
        });

        document.getElementById("register-password").addEventListener("input", () => {
            this.validatePasswordField();
        });

        document.getElementById("register-first").addEventListener("blur", () => {
            this.validateNameField('first');
        });

        document.getElementById("register-last").addEventListener("blur", () => {
            this.validateNameField('last');
        });

        document.getElementById("register-email").addEventListener("blur", () => {
            this.validateEmailField('register');
        });

        document.getElementById("login-email").addEventListener("blur", () => {
            this.validateEmailField('login');
        });

        document.getElementById("register-phone").addEventListener("input", (e) => {
            this.simplePhoneFormat(e.target);
        });

        document.getElementById("register-phone").addEventListener("blur", () => {
            this.validatePhoneField();
        });

        document.querySelectorAll('.auth__input').forEach(input => {
            input.addEventListener('input', () => {
                this.clearFieldError(input.id);
            });
        });
    }

    simplePhoneFormat(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        if (value.length > 0) {
            let formattedValue = '+7';
            if (value.length > 1) {
                formattedValue += ' (' + value.substring(1, 4);
            }
            if (value.length > 4) {
                formattedValue += ') ' + value.substring(4, 7);
            }
            if (value.length > 7) {
                formattedValue += '-' + value.substring(7, 9);
            }
            if (value.length > 9) {
                formattedValue += '-' + value.substring(9, 11);
            }
            input.value = formattedValue;
        } else {
            input.value = '+7 ';
        }
    }

    validateRegisterForm() {
        let isValid = true;
        if (!this.validateLoginField()) isValid = false;
        if (!this.validatePasswordField()) isValid = false;
        if (!this.validateNameField('first')) isValid = false;
        if (!this.validateNameField('last')) isValid = false;
        if (!this.validateEmailField('register')) isValid = false;
        if (!this.validatePhoneField()) isValid = false;
        return isValid;
    }

    validateLoginForm() {
        let isValid = true;
        if (!this.validateEmailField('login')) isValid = false;
        if (!this.validatePasswordLogin()) isValid = false;
        return isValid;
    }

    validateLoginField() {
        const loginInput = document.getElementById("register-login");
        const login = loginInput.value.trim();
        const errorElement = document.getElementById("register-login-error");

        if (!login) {
            this.showFieldError(loginInput, errorElement, "Логин обязателен для заполнения");
            return false;
        }
        if (login.length < 3) {
            this.showFieldError(loginInput, errorElement, "Логин должен содержать минимум 3 символа");
            return false;
        }
        if (!/^[a-zA-Z0-9_]+$/.test(login)) {
            this.showFieldError(loginInput, errorElement, "Логин может содержать только буквы, цифры и подчеркивание");
            return false;
        }
        this.showFieldSuccess(loginInput, errorElement);
        return true;
    }

    validatePasswordField() {
        const passwordInput = document.getElementById("register-password");
        const password = passwordInput.value;
        const errorElement = document.getElementById("register-password-error");
        const strengthElement = document.getElementById("password-strength");

        if (!password) {
            this.showFieldError(passwordInput, errorElement, "Пароль обязателен для заполнения");
            if (strengthElement) strengthElement.textContent = "";
            return false;
        }
        if (password.length < 6) {
            this.showFieldError(passwordInput, errorElement, "Пароль должен содержать минимум 6 символов");
            if (strengthElement) {
                strengthElement.textContent = "Слабый пароль";
                strengthElement.className = "password-strength weak";
            }
            return false;
        }

        const strength = this.checkPasswordStrength(password);
        if (strengthElement) {
            const strengthText = { weak: "Слабый", medium: "Средний", strong: "Сильный" };
            strengthElement.textContent = strengthText[strength];
            strengthElement.className = `password-strength ${strength}`;
        }

        if (strength === 'weak') {
            this.showFieldError(passwordInput, errorElement, "Используйте буквы в разных регистрах, цифры и специальные символы");
            return false;
        }

        this.showFieldSuccess(passwordInput, errorElement);
        return true;
    }

    validatePasswordLogin() {
        const passwordInput = document.getElementById("login-password");
        const password = passwordInput.value;
        const errorElement = document.getElementById("login-password-error");

        if (!password) {
            this.showFieldError(passwordInput, errorElement, "Пароль обязателен для заполнения");
            return false;
        }
        this.clearFieldError(passwordInput.id);
        return true;
    }

    checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        if (strength <= 2) return 'weak';
        if (strength <= 4) return 'medium';
        return 'strong';
    }

    validateNameField(type) {
        const fieldId = type === 'first' ? 'register-first' : 'register-last';
        const fieldName = type === 'first' ? 'Имя' : 'Фамилия';
        const nameInput = document.getElementById(fieldId);
        const name = nameInput.value.trim();
        const errorElement = document.getElementById(`${fieldId}-error`);

        if (!name) {
            this.showFieldError(nameInput, errorElement, `${fieldName} обязательно для заполнения`);
            return false;
        }
        if (name.length < 2) {
            this.showFieldError(nameInput, errorElement, `${fieldName} должно содержать минимум 2 символа`);
            return false;
        }
        if (!/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/.test(name)) {
            this.showFieldError(nameInput, errorElement, `${fieldName} может содержать только буквы, пробелы и дефисы`);
            return false;
        }
        this.showFieldSuccess(nameInput, errorElement);
        return true;
    }

    validateEmailField(formType) {
        const fieldId = formType === 'login' ? 'login-email' : 'register-email';
        const emailInput = document.getElementById(fieldId);
        const email = emailInput.value.trim();
        const errorElement = document.getElementById(`${fieldId}-error`);

        if (!email) {
            this.showFieldError(emailInput, errorElement, "Email обязателен для заполнения");
            return false;
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            this.showFieldError(emailInput, errorElement, "Введите корректный email адрес");
            return false;
        }
        this.showFieldSuccess(emailInput, errorElement);
        return true;
    }

    validatePhoneField() {
        const phoneInput = document.getElementById("register-phone");
        const phoneValue = phoneInput.value;
        const errorElement = document.getElementById("register-phone-error");

        let phoneDigits = phoneValue.replace(/[^\d]/g, '');
        if (phoneDigits.startsWith('8')) {
            phoneDigits = '7' + phoneDigits.substring(1);
        }
        if (!phoneDigits.startsWith('7') && phoneDigits.length > 0) {
            phoneDigits = '7' + phoneDigits;
        }

        if (!phoneDigits) {
            this.showFieldError(phoneInput, errorElement, "Телефон обязателен для заполнения");
            return false;
        }
        if (phoneDigits.length !== 11) {
            this.showFieldError(phoneInput, errorElement, "Номер телефона должен содержать 11 цифр");
            return false;
        }
        if (!/^7\d{10}$/.test(phoneDigits)) {
            this.showFieldError(phoneInput, errorElement, "Введите корректный номер телефона");
            return false;
        }

        const formattedPhone = this.formatPhoneNumber(phoneDigits);
        phoneInput.value = formattedPhone;
        this.showFieldSuccess(phoneInput, errorElement);
        return true;
    }

    formatPhoneNumber(phoneDigits) {
        return `+7 (${phoneDigits.substring(1, 4)}) ${phoneDigits.substring(4, 7)}-${phoneDigits.substring(7, 9)}-${phoneDigits.substring(9, 11)}`;
    }

    showFieldError(input, errorElement, message) {
        input.classList.add('error');
        input.classList.remove('valid');
        errorElement.textContent = message;
        errorElement.classList.add('show');
    }

    showFieldSuccess(input, errorElement) {
        input.classList.remove('error');
        input.classList.add('valid');
        errorElement.classList.remove('show');
    }

    clearFieldError(fieldId) {
        const input = document.getElementById(fieldId);
        const errorElement = document.getElementById(`${fieldId}-error`);
        if (input && errorElement) {
            input.classList.remove('error', 'valid');
            errorElement.classList.remove('show');
        }
    }

    clearAllErrors() {
        document.querySelectorAll('.auth__error').forEach(error => {
            error.classList.remove('show');
        });
        document.querySelectorAll('.auth__input').forEach(input => {
            input.classList.remove('error', 'valid');
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new AuthValidation();
});