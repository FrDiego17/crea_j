@extends('layouts.auth-master')

@section('content')
    <div class="cont">
        <div class="form sign-in">
            <!-- Registro -->
            <form class="Form" action="/register" method="POST">
                @csrf
                
                <h2>Registrate</h2>
                @include('layouts.partials.messages')
                
                <label>
                    <span>Usuario</span>
                    <input name="username" type="text" value="{{ old('username') }}" />
                </label>
               
                <label>
                    <span>Email</span>
                    <input name="email" type="email" value="{{ old('email') }}" />
                </label>

                <label>
                    <span>Contraseña</span>
                    <input name="password" type="password" />
                </label>

                <label>
                    <span>Confirmar Contraseña</span>
                    <input name="password_confirmation" type="password" />
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
                    @include('layouts.partials.messages')
                    
                    @if ($errors->has('clerk_user'))
                        <div class="alert alert-info" style="background-color: #e3f2fd; border: 1px solid #2196f3; padding: 15px; margin: 15px 0; border-radius: 5px;">
                            <strong>Información:</strong> {{ $errors->first('clerk_user') }}
                            
                            @if ($errors->has('show_password_setup'))
                                <div style="margin-top: 10px;">
                                    <button type="button" onclick="setupPassword('{{ $errors->first('user_email') }}')" 
                                            style="background: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                                        Crear Password para Web
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    <label>
                        <span>Email</span>
                        <input name="username" type="email" value="{{ old('username') }}" />
                    </label>
                    <label>
                        <span>Password</span>
                        <input name="password" type="password" />
                    </label>

                    <p class="forgot-pass">Olvidaste la contraseña</p>

                    <input type="submit" value="Inicia" class="submit"/>
                </form>
                
            </div>
        </div>
    </div>

    <script>
        function setupPassword(email) {
            window.location.href = '/set-password?email=' + encodeURIComponent(email);
        }
    </script>
@endsection