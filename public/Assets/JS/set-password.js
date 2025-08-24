

class PasswordModal {
    constructor() {
        this.modal = null;
        this.form = null;
        this.submitBtn = null;
        this.isInitialized = false;
        this.init();
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.bindEvents());
        } else {
            this.bindEvents();
        }
    }

    bindEvents() {
        this.modal = document.getElementById('passwordModal');
        this.form = document.getElementById('setPasswordForm');
        this.submitBtn = document.getElementById('submitBtn');

        if (!this.modal || !this.form || !this.submitBtn) {
            console.warn('Password modal elements not found');
            return;
        }

        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isModalOpen()) {
                this.close();
            }
        });

        const modalContent = this.modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => e.stopPropagation());
        }

        const overlay = this.modal.querySelector('.modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', () => this.close());
        }

        this.isInitialized = true;
        console.log('Password modal initialized successfully');
    }


    open(email) {
        if (!this.isInitialized || !this.modal) {
            console.error('Modal not initialized');
            return;
        }

        console.log('Opening password modal for:', email);

        const emailInput = document.getElementById('userEmail');
        if (emailInput) {
            emailInput.value = email;
        }

        this.modal.style.display = 'block';
        this.modal.style.zIndex = '9999';
        
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');

        setTimeout(() => {
            const firstInput = this.modal.querySelector('input[type="password"]');
            if (firstInput) {
                firstInput.focus();
            }
        }, 100);
    }

    close() {
        if (!this.modal) return;

        this.modal.style.display = 'none';
        
        document.body.style.overflow = 'auto';
        document.body.classList.remove('modal-open');
        
        if (this.form) {
            this.form.reset();
        }
        this.hideMessage('modalErrors');
        this.hideMessage('modalSuccess');
        
        this.resetSubmitButton();

        console.log('Password modal closed');
    }

    isModalOpen() {
        return this.modal && this.modal.style.display === 'block';
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        if (!this.form) return;

        console.log('Submitting password form');

        this.setLoadingState(true);
        this.hideMessage('modalErrors');
        this.hideMessage('modalSuccess');

        const formData = new FormData(this.form);
        
        try {
            const response = await this.submitPassword(formData);
            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message);
                setTimeout(() => {
                    this.close();
                    window.location.reload();
                }, 2000);
            } else {
                this.showErrors(result.errors || { general: [result.message || 'Error desconocido'] });
            }

        } catch (error) {
            console.error('Password setup error:', error);
            this.showErrors({ 
                general: ['Error de conexión. Intenta nuevamente.'] 
            });
        }

        this.setLoadingState(false);
    }


    async submitPassword(formData) {
        const token = document.querySelector('[name=_token]')?.value || 
                     document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!token) {
            throw new Error('CSRF token not found');
        }
        
        return fetch('/set-password', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData
        });
    }


    showSuccess(message) {
        const successDiv = document.getElementById('modalSuccess');
        if (successDiv) {
            successDiv.textContent = message;
            successDiv.style.display = 'block';
        }
    }


    showErrors(errors) {
        const errorsDiv = document.getElementById('modalErrors');
        if (!errorsDiv) return;

        let errorText = '';
        
        Object.values(errors).forEach(errorArray => {
            if (Array.isArray(errorArray)) {
                errorArray.forEach(error => {
                    errorText += error + '<br>';
                });
            } else {
                errorText += errorArray + '<br>';
            }
        });

        errorsDiv.innerHTML = errorText;
        errorsDiv.style.display = 'block';
    }


    hideMessage(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = 'none';
        }
    }

    setLoadingState(isLoading) {
        if (!this.submitBtn) return;

        if (isLoading) {
            this.submitBtn.disabled = true;
            this.submitBtn.dataset.originalText = this.submitBtn.textContent;
            this.submitBtn.textContent = '⏳ Procesando...';
        } else {
            this.resetSubmitButton();
        }
    }


    resetSubmitButton() {
        if (!this.submitBtn) return;

        this.submitBtn.disabled = false;
        const originalText = this.submitBtn.dataset.originalText || '🚀 Establecer Contraseña';
        this.submitBtn.textContent = originalText;
    }
}


const passwordModal = new PasswordModal();


function openPasswordModal(email) {
    console.log('openPasswordModal called with:', email);
    if (passwordModal && typeof passwordModal.open === 'function') {
        passwordModal.open(email);
    } else {
        console.error('Password modal not available');

        setTimeout(() => {
            if (passwordModal && typeof passwordModal.open === 'function') {
                passwordModal.open(email);
            }
        }, 500);
    }
}

function closePasswordModal() {
    if (passwordModal && typeof passwordModal.close === 'function') {
        passwordModal.close();
    }
}