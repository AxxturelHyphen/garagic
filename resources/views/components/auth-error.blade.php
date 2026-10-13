{{-- Mensaje de error de un campo de un formulario con `validatedForm` (resources/js/garagic/forms.js).
     Lo rellena Alpine con el error de cliente o con el del servidor; `x-bind="field('campo')"` en el input
     lo enlaza por aria-describedby con este id. role="alert" para que los lectores de pantalla lo anuncien. --}}
@props(['name'])

<p id="{{ $name }}-error" role="alert" class="auth-error" x-show="hasError('{{ $name }}')" x-cloak>
    <flux:icon.triangle-alert variant="micro" />
    <span x-text="error('{{ $name }}')"></span>
</p>
