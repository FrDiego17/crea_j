@extends('layouts.auth-master')

@section('content')
    <div class="cont">
        <div class="form sign-in">
            <!-- Registro xd -->
            <form class="Form" action="/register" method="POST">
                @csrf
                
                <h2>Registrate</h2>
                @include('layouts.partials.messages')
                <label>
                    <span>Usuario</span>
                    <input name="username" type="text" />
                </label>
               
                <label>
                    <span>Email</span>
                    <input name="email" type="email" />
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

            <!-- Inicio xd -->
            <div class="form sign-up">
                <form class="Form1" action="/login" method="POST">
                    @csrf
                    
                    <h2>Inicia Sesion</h2>
                    @include('layouts.partials.messages')
                    <label>
                        <span>Usuario/Email</span>
                        <input name="username" id="" type="text" />
                    </label>
                    <label>
                        <span>Password</span>
                        <input name="password" id="" type="password" />
                    </label>

                    <p class="forgot-pass">Olvidaste la contraseña</p>

                    <input type="submit" value="Inicia" class="submit"/>
                </form>
                
            </div>
        </div>
    </div>
@endsection