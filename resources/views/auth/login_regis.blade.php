@extends('layouts.auth-master')

@section('content')
<div class="cont {{ (session('show_login_errors') || $errors->has('login_error') || $errors->has('clerk_user')) ? 's--signup' : '' }}">
    <div class="form sign-in">
        <!-- Registro -->
        <form class="Form" action="/register" method="POST">
            @csrf
            
            <h2>Registrate</h2>
            
            {{-- Mostrar errores de registro --}}
            @if(session('show_register_errors') || $errors->has('register_error'))
                <div class="alert alert-danger" style="background-color: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0; border-radius: 5px;">
                    @if($errors->has('register_error'))
                        <div>{{ $errors->first('register_error') }}</div>
                    @endif
                    @if($errors->has('username'))
                        <div>{{ $errors->first('username') }}</div>
                    @endif
                    @if($errors->has('email'))
                        <div>{{ $errors->first('email') }}</div>
                    @endif
                    @if($errors->has('password'))
                        <div>{{ $errors->first('password') }}</div>
                    @endif
                </div>
            @endif

            {{-- Mensaje de éxito --}}
            @if(session('success') && !session('show_login_errors'))
                <div class="alert alert-success" style="background-color: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0; border-radius: 5px;">
                    {{ session('success') }}
                </div>
            @endif
            
            <label>
                <span>Usuario</span>
                <input name="username" type="text" value="{{ old('username') }}" required />
            </label>
           
            <label>
                <span>Email</span>
                <input name="email" type="email" value="{{ old('email') }}" required />
            </label>

            <label>
                <span>Contraseña</span>
                <input name="password" type="password" required />
            </label>

            <label>
                <span>Confirmar Contraseña</span>
                <input name="password_confirmation" type="password" required />
            </label>

            <input type="submit" value="Registrate" class="submit"/>
        </form>
    </div>

    <div class="sub-cont">
        <div class="img">
            <div class="img__text m--up">
                <h2 class="Text">HOLA</h2>
                <p class="Text">Ya tienes una cuenta?</p>
            </div>

            <div class="img__text m--in">
                <h2 class="Text">HOLA</h2>
                <p class="Text">No tienes una cuenta?</p>
            </div>

            <div class="img__btn">
                <span class="m--up">Inicia Sesion</span>
                <span class="m--in">Registrate</span>
            </div>
        </div>

        <!-- Inicio de Sesión -->
        <div class="form sign-up">
            <form class="Form1" action="/login" method="POST">
                @csrf
                
                <h2>Inicia Sesion</h2>
                
                {{-- Mostrar errores de login --}}
                @if(session('show_login_errors') || $errors->has('login_error'))
                    @if(!$errors->has('clerk_user'))
                        <div class="alert alert-danger" style="background-color: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0; border-radius: 5px;">
                            @if($errors->has('login_error'))
                                <div>{{ $errors->first('login_error') }}</div>
                            @endif
                            @if($errors->has('username'))
                                <div>{{ $errors->first('username') }}</div>
                            @endif
                            @if($errors->has('password'))
                                <div>{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    @endif
                @endif

                {{-- Info sobre usuarios de Clerk --}}
                @if ($errors->has('clerk_user'))
                    <div class="alert alert-info" style="background-color: #e3f2fd; border: 1px solid #2196f3; padding: 15px; margin: 15px 0; border-radius: 5px;">
                        <strong>Información:</strong> {{ $errors->first('clerk_user') }}
                        
                        @if ($errors->has('show_password_setup'))
                            <div style="margin-top: 10px;">
                                <button type="button" onclick="openPasswordModal('{{ $errors->first('user_email') }}')" 
                                        style="background: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                                    Crear Password para Web
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                <label>
                    <span>Email</span>
                    <input name="username" type="email" value="{{ old('username') }}" required />
                </label>
                <label>
                    <span>Password</span>
                    <input name="password" type="password" required />
                </label>

                <p class="forgot-pass">Olvidaste la contraseña</p>

                <input type="submit" value="Inicia" class="submit"/>
            </form>
        </div>
    </div>
</div>

<!-- Modal para establecer contraseña -->
<div id="passwordModal" class="password-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
    <div class="modal-overlay" onclick="closePasswordModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6);"></div>
    <div class="modal-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; width: 90%; max-width: 480px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5); z-index: 10000; overflow: hidden;">
        <div class="modal-header" style="padding: 25px 30px 20px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 255, 255, 0.2); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: white; font-size: 22px; font-weight: 600; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">Establecer Contraseña Web</h3>
            <button class="close-btn" onclick="closePasswordModal()" style="background: rgba(255, 255, 255, 0.2); border: none; border-radius: 50%; font-size: 20px; cursor: pointer; color: white; padding: 0; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>
        
        <div class="modal-body" style="padding: 30px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
            <p style="margin: 0 0 25px 0; color: #555; font-size: 15px; text-align: center; line-height: 1.5;">Crea una contraseña segura para acceder desde tu navegador web y disfrutar de todas las funcionalidades.</p>
            
            <div id="modalErrors" class="alert alert-danger" style="display: none; padding: 15px 20px; margin: 20px 0; border-radius: 12px; font-size: 14px; background: linear-gradient(135deg, #ff6b6b, #ffa8a8); border: none; color: white;"></div>
            <div id="modalSuccess" class="alert alert-success" style="display: none; padding: 15px 20px; margin: 20px 0; border-radius: 12px; font-size: 14px; background: linear-gradient(135deg, #51cf66, #8ce99a); border: none; color: white;"></div>
            
            <form id="setPasswordForm">
                <input type="hidden" id="userEmail" name="email">
                
                <label style="display: block; margin-bottom: 20px;">
                    <span style="display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 14px;">Nueva Contraseña</span>
                    <input type="password" id="newPassword" name="password" required minlength="8" style="width: 100%; padding: 15px; border: 2px solid #e1e5e9; border-radius: 12px; font-size: 15px; box-sizing: border-box; background: white;">
                </label>
                
                <label style="display: block; margin-bottom: 20px;">
                    <span style="display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 14px;">Confirmar Contraseña</span>
                    <input type="password" id="confirmPassword" name="password_confirmation" required style="width: 100%; padding: 15px; border: 2px solid #e1e5e9; border-radius: 12px; font-size: 15px; box-sizing: border-box; background: white;">
                </label>
                
                <div class="modal-buttons" style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="button" onclick="closePasswordModal()" class="btn-cancel" style="flex: 1; padding: 15px 25px; border: none; border-radius: 12px; cursor: pointer; font-size: 15px; font-weight: 600; background: #f8f9fa; color: #6c757d; border: 2px solid #e9ecef;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-submit" id="submitBtn" style="flex: 1; padding: 15px 25px; border: none; border-radius: 12px; cursor: pointer; font-size: 15px; font-weight: 600; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                        Establecer Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/password-modal.css') }}">
<style>
/* Fallback CSS en caso de que no cargue el archivo externo */
#passwordModal.password-modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 9999 !important;
    display: none;
}

#passwordModal .modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
}

#passwordModal .modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    width: 90%;
    max-width: 480px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    z-index: 10000 !important;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/password-modal.js') }}"></script>
<script>
// JavaScript para alternar entre login y registro
document.addEventListener('DOMContentLoaded', function() {
    const hasLoginErrors = {{ (session('show_login_errors') || $errors->has('login_error') || $errors->has('clerk_user')) ? 'true' : 'false' }};
    
    // Función para agregar el toggle
    function addToggleListener() {
        const imgBtn = document.querySelector('.img__btn');
        if (imgBtn && !imgBtn.hasAttribute('data-toggle-added')) {
            imgBtn.addEventListener('click', function (e) {
                // Prevenir que interfiera con otros botones
                if (e.target.closest('.alert') || e.target.closest('.password-modal')) {
                    return;
                }
                document.querySelector('.cont').classList.toggle('s--signup');
            });
            imgBtn.setAttribute('data-toggle-added', 'true');
        }
    }
    
    if (!hasLoginErrors) {
        // Si no hay errores, agregar inmediatamente
        addToggleListener();
    } else {
        // Si hay errores de login, asegurar modo login
        document.querySelector('.cont').classList.add('s--signup');
        
        // Agregar toggle después de un delay para evitar conflictos
        setTimeout(function() {
            addToggleListener();
        }, 1000); // Reducido a 1 segundo
    }
});

// Función global para el modal (mantener para compatibilidad)
function openPasswordModal(email) {
    console.log('=== DEBUG openPasswordModal ===');
    console.log('Email:', email);
    
    const modal = document.getElementById('passwordModal');
    const emailInput = document.getElementById('userEmail');
    
    console.log('Modal element found:', !!modal);
    console.log('Email input found:', !!emailInput);
    
    if (modal && emailInput) {
        emailInput.value = email;
        modal.style.display = 'block';
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.zIndex = '9999';
        document.body.style.overflow = 'hidden';
        console.log('Modal opened successfully');
        
        // Focus en el primer input
        setTimeout(() => {
            const firstInput = document.getElementById('newPassword');
            if (firstInput) firstInput.focus();
        }, 100);
    } else {
        console.error('Modal elements not found');
    }
    
    console.log('=== END DEBUG ===');
}

function closePasswordModal() {
    console.log('Closing modal...');
    const modal = document.getElementById('passwordModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Limpiar formulario
        const form = document.getElementById('setPasswordForm');
        if (form) form.reset();
        
        // Ocultar mensajes
        const errorDiv = document.getElementById('modalErrors');
        const successDiv = document.getElementById('modalSuccess');
        if (errorDiv) errorDiv.style.display = 'none';
        if (successDiv) successDiv.style.display = 'none';
        
        console.log('Modal closed successfully');
    }
}

// Manejar envío del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('setPasswordForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            
            // Loading state
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Procesando...';
            
            // Ocultar mensajes anteriores
            document.getElementById('modalErrors').style.display = 'none';
            document.getElementById('modalSuccess').style.display = 'none';
            
            const formData = new FormData(this);
            
            try {
                const token = document.querySelector('[name=_token]')?.value || 
                             document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const response = await fetch('/set-password', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const successDiv = document.getElementById('modalSuccess');
                    successDiv.textContent = result.message;
                    successDiv.style.display = 'block';
                    
                    setTimeout(() => {
                        closePasswordModal();
                        location.reload();
                    }, 2000);
                } else {
                    const errorsDiv = document.getElementById('modalErrors');
                    let errorText = '';
                    
                    if (result.errors) {
                        Object.values(result.errors).forEach(errorArray => {
                            if (Array.isArray(errorArray)) {
                                errorArray.forEach(error => errorText += error + '<br>');
                            } else {
                                errorText += errorArray + '<br>';
                            }
                        });
                    } else {
                        errorText = result.message || 'Error desconocido';
                    }
                    
                    errorsDiv.innerHTML = errorText;
                    errorsDiv.style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                const errorsDiv = document.getElementById('modalErrors');
                errorsDiv.textContent = 'Error de conexión. Intenta nuevamente.';
                errorsDiv.style.display = 'block';
            }
            
            // Restaurar botón
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }
});
</script>
@endpush
@endsection