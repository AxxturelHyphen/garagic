{{-- Dorso común de las cartas: xilografía real (tarot piamontés, 1865) como máscara de tinta y, encima, un cartucho con los datos secundarios (km, matrícula, ITV). Se usa dentro de <x-tarot-card>; sin datos queda solo la xilografía (carta boca abajo). --}}
@props(['data' => null])
<div {{ $attributes->class('tc__paper tc__paper--back') }} style="--back-art: url('{{ asset('img/ornament/card-back.webp') }}')">
    <div class="tc__backart" aria-hidden="true"></div>

    @if ($data)
        <dl class="tc__cartouche">
            @foreach ($data as $label => $value)
                <div>
                    <dt>{{ $label }}</dt>
                    <dd>{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    @endif

    <div class="tc__grain"></div>
    <div class="tc__stains"></div>
</div>
