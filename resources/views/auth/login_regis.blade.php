@extends('layouts.auth-master')

@section('content')
    <div class="cont">
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

                    {{-- Info sobre usuarios de Clerk --}}
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

    <script>
        function setupPassword(email) {
            window.location.href = '/set-password?email=' + encodeURIComponent(email);
        }
    </script>
@endsection