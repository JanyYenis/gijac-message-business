@extends('layouts.app')

@section('content')
    <div class="auth-card">
        <div class="auth-head">
            <h2>{{ __('Bienvenido de nuevo') }}</h2>
            <p>{{ __('Inicia sesión en tu cuenta empresarial GIJAC') }}</p>
        </div>

        <div class="social">
            <a class="btn-social magnetic" type="button" href="{{ route('login-google') }}">
                <svg width="18" height="18" viewBox="0 0 48 48">
                    <path fill="#FFC107"
                        d="M43.6 20.5H42V20H24v8h11.3C33.9 32.5 29.4 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.2-.1-2.3-.4-3.5z" />
                    <path fill="#FF3D00"
                        d="M6.3 14.7l6.6 4.8C14.6 15.1 18.9 12 24 12c3.1 0 5.8 1.2 7.9 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z" />
                    <path fill="#4CAF50"
                        d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35 26.7 36 24 36c-5.3 0-9.8-3.4-11.3-8.1l-6.5 5C9.5 39.6 16.2 44 24 44z" />
                    <path fill="#1976D2"
                        d="M43.6 20.5H42V20H24v8h11.3c-.7 2-2 3.7-3.7 5l6.2 5.2C41.6 34.6 44 29.7 44 24c0-1.2-.1-2.3-.4-3.5z" />
                </svg>{{ __('Continuar con Google') }}</a>
            <a class="btn-social btn-ms magnetic" type="button" href="{{ route('login-outlook') }}">
                <svg width="18" height="18" viewBox="0 0 48 48">
                    <rect x="4" y="4" width="19" height="19" fill="#F25022" />
                    <rect x="25" y="4" width="19" height="19" fill="#7FBA00" />
                    <rect x="4" y="25" width="19" height="19" fill="#00A4EF" />
                    <rect x="25" y="25" width="19" height="19" fill="#FFB900" />
                </svg>{{ __('Continuar con Microsoft') }}</a>
        </div>

        <div class="divider">{{ __('o con email') }}</div>

        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" id="kt_sign_in_form"
            data-kt-redirect-url="{{ route('login') }}" action="{{ route('login') }}" method="POST">
            <div class="field">
                <input id="email" type="email" name="email" placeholder=" " required />
                <label for="email">{{ __('Correo electrónico') }}</label>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="field">
                <input id="password" type="password" name="password" placeholder=" " required />
                <label for="password">{{ __('Contraseña') }}</label>
                <span class="eye" id="togglePw">
                    <i class="fa-regular fa-eye"></i>
                </span>
                <div class="strength">
                    <i id="strengthBar"></i>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="row-opts">
                <label class="check">
                    <input type="checkbox" checked name="remember">{{ __('Recordarme') }}</label>
                <a href="{{ route('password.request') }}" class="link">{{ __('¿Olvidaste tu contraseña?') }}</a>
            </div>

            <button class="btn-primary-x magnetic" id="kt_sign_in_submit" type="submit">
                <i class="fa-solid fa-right-to-bracket me-1"></i>{{ __('Iniciar sesión') }}</button>
            <a class="btn-outline-x magnetic text-center" type="button" href="{{ route('register') }}">
                <i class="fa-solid fa-rocket me-1"></i>{{ __('Comenzar prueba gratuita') }}</a>
        </form>
    </div>
@endsection

@section('js')
    <!-- Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
    <script>{{ __('$(function() {
            // Password toggle
            $(\'#togglePw\').on(\'click\', function() {
                const $i = $(\'#password\');
                const t = $i.attr(\'type\') === \'password\' ? \'text\' : \'password\';
                $i.attr(\'type\', t);
                $(this).find(\'i\').attr(\'class\', t === \'password\' ? \'fa-regular fa-eye\' :
                    \'fa-regular fa-eye-slash\');
            });

            // Password strength
            $(\'#password\').on(\'input\', function() {
                const v = this.value;
                let s = 0;
                if (v.length >= 6) s += 25;
                if (/[A-Z]/.test(v)) s += 25;
                if (/[0-9]/.test(v)) s += 25;
                if (/[^A-Za-z0-9]/.test(v)) s += 25;
                $(\'#strengthBar\').css(\'width\', s + \'%\');
            });
        });') }</script>
@endsection
