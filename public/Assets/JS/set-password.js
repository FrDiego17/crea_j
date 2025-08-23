function setupToggleButtons() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fieldId = this.getAttribute('data-target');
            const field = document.getElementById(fieldId);
            
            if (field.type === 'password') {
                field.type = 'text';
                this.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                `;
            } else {
                field.type = 'password';
                this.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                        <path d="m10.73 5.08-1.39.87A11.5 11.5 0 0 0 2 12c.64.9 1.32 1.72 2.05 2.46"></path>
                        <path d="m14.27 18.92 1.39-.87A11.5 11.5 0 0 0 22 12c-.64-.9-1.32-1.72-2.05-2.46"></path>
                        <line x1="2" y1="2" x2="22" y2="22"></line>
                    </svg>
                `;
            }
        });
    });
}

function initPasswordValidation() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    
    if (!password || !confirmPassword) return;
    
    function validatePasswords() {
        if (confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.style.borderColor = '#ef4444';
            confirmPassword.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
        } else {
            confirmPassword.style.borderColor = '#e1e5e9';
            confirmPassword.style.boxShadow = 'none';
        }
    }
    
    confirmPassword.addEventListener('input', validatePasswords);
    password.addEventListener('input', validatePasswords);
}

document.addEventListener('DOMContentLoaded', function() {
    setupToggleButtons();
    initPasswordValidation();
    
    const submitBtn = document.querySelector('.submit-btn');
    const form = document.querySelector('.password-form');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.style.opacity = '0.7';
            submitBtn.innerHTML = `
                <span>Estableciendo...</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite;">
                    <path d="M21 12a9 9 0 11-6.219-8.56"/>
                </svg>
            `;
            submitBtn.disabled = true;
        });
    }
});

const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);