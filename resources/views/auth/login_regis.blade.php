@extends('layouts.auth-master')

@section('content')
<div class="cont">
    <!-- Tu formulario de login/registro existente -->
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

        <!-- Inicio -->
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
<div id="passwordModal" class="password-modal" style="display: none;">
    <div class="modal-overlay" onclick="closePasswordModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Establecer Contraseña Web</h3>
            <button class="close-btn" onclick="closePasswordModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <p>Crea una contraseña para acceder desde el navegador web:</p>
            
            <div id="modalErrors" class="alert alert-danger" style="display: none;"></div>
            <div id="modalSuccess" class="alert alert-success" style="display: none;"></div>
            
            <form id="setPasswordForm">
                <input type="hidden" id="userEmail" name="email">
                
                <label>
                    <span>Nueva Contraseña</span>
                    <input type="password" id="newPassword" name="password" required minlength="8">
                </label>
                
                <label>
                    <span>Confirmar Contraseña</span>
                    <input type="password" id="confirmPassword" name="password_confirmation" required>
                </label>
                
                <div class="modal-buttons">
                    <button type="button" onclick="closePasswordModal()" class="btn-cancel">
                        ❌ Cancelar
                    </button>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        🚀 Establecer Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.password-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    width: 90%;
    max-width: 480px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translate(-50%, -60%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.modal-header {
    padding: 25px 30px 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: white;
    font-size: 22px;
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.close-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    color: white;
    padding: 0;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.modal-body {
    padding: 30px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.modal-body p {
    margin: 0 0 25px 0;
    color: #555;
    font-size: 15px;
    text-align: center;
    line-height: 1.5;
}

.modal-body label {
    display: block;
    margin-bottom: 20px;
}

.modal-body label span {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.modal-body input[type="password"] {
    width: 100%;
    padding: 15px;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    font-size: 15px;
    box-sizing: border-box;
    transition: all 0.3s ease;
    background: white;
}

.modal-body input[type="password"]:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.modal-buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-cancel, .btn-submit {
    flex: 1;
    padding: 15px 25px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-cancel {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #e9ecef;
}

.btn-cancel:hover {
    background: #e9ecef;
    color: #495057;
    transform: translateY(-1px);
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-submit:disabled {
    background: #cccccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.alert {
    padding: 15px 20px;
    margin: 20px 0;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.4;
}

.alert-danger {
    background: linear-gradient(135deg, #ff6b6b, #ffa8a8);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.alert-success {
    background: linear-gradient(135deg, #51cf66, #8ce99a);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(81, 207, 102, 0.3);
}
</style>

<script>
function openPasswordModal(email) {
    document.getElementById('userEmail').value = email;
    document.getElementById('passwordModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevenir scroll del body
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Limpiar formulario
    document.getElementById('setPasswordForm').reset();
    document.getElementById('modalErrors').style.display = 'none';
    document.getElementById('modalSuccess').style.display = 'none';
}

document.getElementById('setPasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    
    // Deshabilitar botón y mostrar loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Procesando...';
    
    // Limpiar mensajes anteriores
    document.getElementById('modalErrors').style.display = 'none';
    document.getElementById('modalSuccess').style.display = 'none';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/set-password', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Mostrar mensaje de éxito
            const successDiv = document.getElementById('modalSuccess');
            successDiv.textContent = result.message;
            successDiv.style.display = 'block';
            
            // Cerrar modal después de 2 segundos y recargar página
            setTimeout(() => {
                closePasswordModal();
                location.reload();
            }, 2000);
            
        } else {
            // Mostrar errores
            const errorsDiv = document.getElementById('modalErrors');
            let errorText = '';
            
            if (result.errors) {
                Object.values(result.errors).forEach(errorArray => {
                    errorArray.forEach(error => {
                        errorText += error + '<br>';
                    });
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

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('passwordModal').style.display === 'block') {
        closePasswordModal();
    }
});
</script>
@endsection