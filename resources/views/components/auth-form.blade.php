{{--
    <form> POST de las pantallas de acceso con la validación en el navegador ya enchufada.

    POR QUÉ: el navegador avisa al instante (campo vacío, correo mal escrito, contraseñas distintas) con
    `validatedForm`, pero el SERVIDOR (Fortify) vuelve a validar siempre y tiene la última palabra: si el
    formulario es válido aquí no se toca el envío, y lo que el servidor rechace entra por `serverErrors`
    y se pinta en el mismo sitio que los errores de cliente.

    PROPS
      action  URL del POST (route('login.store')…)
      rules   reglas por campo: ['email' => ['required', 'email'], …] (ver forms.js)
    Los campos van dentro: <flux:input … x-bind="field('email')" /> + <x-auth-error name="email" />.
--}}
@props(['action', 'rules' => []])
@php
    // Fortify y Laravel devuelven sus mensajes en inglés (aún no hay lang/es). Se traducen aquí los que pueden
    // llegar a estas pantallas; los demás pasan tal cual. Provisional: con locale `es` este bloque sobra.
    $translate = function (string $message): string {
        $exact = [
            'These credentials do not match our records.' => 'Ese correo y esa contraseña no coinciden.',
            'The email has already been taken.' => 'Ese correo ya tiene una cuenta.',
            "We can't find a user with that email address." => 'No hay ninguna cuenta con ese correo.',
            'This password reset token is invalid.' => 'El enlace no es válido o ha caducado. Pide uno nuevo.',
            'Please wait before retrying.' => 'Espera un poco antes de volver a intentarlo.',
            'The password field confirmation does not match.' => 'No coinciden.',
            'The password field must be at least 8 characters.' => 'Mínimo 8 caracteres.',
        ];

        // «Too many login attempts. Please try again in 42 seconds.»
        if (preg_match('/^Too many login attempts\. Please try again in (\d+) seconds\.$/', $message, $found)) {
            return "Demasiados intentos. Espera {$found[1]} segundos.";
        }

        return $exact[$message] ?? $message;
    };

    $serverErrors = collect($errors->messages())
        ->map(fn (array $messages): array => array_map($translate, $messages))
        ->all();
@endphp
<form method="POST" action="{{ $action }}" novalidate
      x-data="validatedForm(@js($rules), @js($serverErrors))"
      x-on:submit="submit($event)"
      {{ $attributes->class('flex flex-col gap-6') }}>
    @csrf

    {{ $slot }}
</form>
