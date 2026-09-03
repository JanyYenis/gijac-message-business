@extends('layouts.app')

@section('content')
    <div class="auth-card">
        <div class="auth-head">
            <h2 >{{ __('Recuperar contraseña') }}</h2>
        </div>
        <div class="divider">{{ __('Email') }}</div>

        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" id="kt_new_password_form"
            data-kt-redirect-url="{{ route('home') }}" action="{{ route('password.update') }}" method="POST">
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="field">
                <input id="email" type="email" name="email" placeholder=" " required value="{{ $email ?? old('email') }}"
                    autocomplete="off" class="@error('email') is-invalid @enderror"/>
                <label for="email">{{ __('Email') }}</label>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="field">
                <input id="password" type="password" name="password" placeholder=" " required
                    autocomplete="off" class="@error('password') is-invalid @enderror"/>
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
            <div class="text-muted">{{ __('Utilice 8 o más caracteres con una combinación de letras, números y símbolos.') }}</div>

            <div class="field">
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder=" " required
                    autocomplete="off" class="@error('password_confirmation') is-invalid @enderror"/>
                <label for="password_confirmation">{{ __('Confirmar Contraseña') }}</label>
                <span class="eye" id="togglePw1">
                    <i class="fa-regular fa-eye"></i>
                </span>
            </div>

            <button type="submit" id="kt_new_password_submit" class="btn-primary-x magnetic">
                <!--begin::Indicator label-->
                <span class="indicator-label">{{ __('Restablecer contraseña') }}</span>
                <!--end::Indicator label-->
            </button>

            <a class="btn-outline-x magnetic text-center" type="button" href="{{ route('login') }}">
                <i class="fa-solid fa-right-to-bracket me-1"></i>{{ __('Iniciar sesión') }}</a>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/custom/authentication/reset-password/new-password.js') }}"></script>
    <script>{{ __('$(function() {
            // Password toggle
            $(\'#togglePw\').on(\'click\', function() {
                const $i = $(\'#password\');
                const t = $i.attr(\'type\') === \'password\' ? \'text\' : \'password\';
                $i.attr(\'type\', t);
                $(this).find(\'i\').attr(\'class\', t === \'password\' ? \'fa-regular fa-eye\' :
                    \'fa-regular fa-eye-slash\');
            });
            // Password toggle
            $(\'#togglePw1\').on(\'click\', function() {
                const $i = $(\'#password_confirmation\');
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
