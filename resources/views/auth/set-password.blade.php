@extends('layouts.auth-master')

@section('content')
<div class="password-setup-container">
    <div class="password-setup-card">
        
        <div class="header-section">
            <div class="icon-container">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <circle cx="12" cy="16" r="1"></circle>
                    <path d="m7 11 V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h2>Crear Password Web</h2>
            <p class="subtitle">Tu cuenta fue creada desde la app móvil. Establece un password para acceder desde la web.</p>
        </div>

        @include('layouts.partials.messages')

        <form action="/set-password" method="POST" class="password-form">
            @csrf
            
            <input type="hidden" name="email" value="{{ $email }}" />

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="{{ $email }}" readonly class="form-input readonly" />
            </div>

            <div class="form-group">
                <label>Nueva Contraseña</label>
                <div class="input-container">
                    <input name="password" type="password" placeholder="Mínimo 6 caracteres" required class="form-input" id="password" />
                    <button type="button" class="toggle-password" data-target="password">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                            <path d="m10.73 5.08-1.39.87A11.5 11.5 0 0 0 2 12c.64.9 1.32 1.72 2.05 2.46"></path>
                            <path d="m14.27 18.92 1.39-.87A11.5 11.5 0 0 0 22 12c-.64-.9-1.32-1.72-2.05-2.46"></path>
                            <line x1="2" y1="2" x2="22" y2="22"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Confirmar Contraseña</label>
                <div class="input-container">
                    <input name="password_confirmation" type="password" placeholder="Repite la contraseña" required class="form-input" id="password_confirmation" />
                    <button type="button" class="toggle-password" data-target="password_confirmation">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                            <path d="m10.73 5.08-1.39.87A11.5 11.5 0 0 0 2 12c.64.9 1.32 1.72 2.05 2.46"></path>
                            <path d="m14.27 18.92 1.39-.87A11.5 11.5 0 0 0 22 12c-.64-.9-1.32-1.72-2.05-2.46"></path>
                            <line x1="2" y1="2" x2="22" y2="22"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <span>Establecer Password</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
            </button>
        </form>

        <div class="back-link">
            <a href="/login">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
                Volver al Login
            </a>
        </div>
    </div>
</div>
@endsection

