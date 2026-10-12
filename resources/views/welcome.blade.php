<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#eee9dc">
        <title>Garagic — tu vehículo, siempre al día</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="garagic-site">
        <header class="site-header">
            <a class="wordmark" href="{{ route('home') }}" aria-label="Garagic, inicio">
                <span class="wordmark-mark" aria-hidden="true">G</span>
                <span>garagic<small>libreta de taller</small></span>
            </a>
            <nav class="site-nav" aria-label="Navegación principal">
                <a href="#como-funciona">cómo funciona</a>
                <a href="#cuaderno">el cuaderno</a>
                <div class="site-nav-actions">
                    @auth
                        <a class="button button-quiet" href="{{ route('dashboard') }}">mi garaje</a>
                    @else
                        @if (Route::has('login'))
                            <a class="button button-quiet" href="{{ route('login') }}">entrar</a>
                        @endif
                        @if (Route::has('register'))
                            <a class="button button-ink" href="{{ route('register') }}">abrir mi garaje <span aria-hidden="true">↗</span></a>
                        @endif
                    @endauth
                </div>
            </nav>
        </header>

        <main>
            <section class="hero" aria-labelledby="hero-title">
                <div class="hero-copy">
                    <p class="eyebrow"><span class="eyebrow-rule"></span> registro de mantenimiento · desde 2026</p>
                    <h1 id="hero-title">Cuida de tu coche.<br><em>Que no se te pase nada.</em></h1>
                    <p class="hero-intro">ITV, seguro, aceite y gastos: todo en su sitio. Una libreta de taller para saber qué toca y cuándo, con una carta para cada vehículo.</p>
                    <div class="hero-actions">
                        @guest
                            @if (Route::has('register'))
                                <a class="button button-ink button-large" href="{{ route('register') }}">empezar mi cuaderno <span aria-hidden="true">→</span></a>
                            @endif
                            @if (Route::has('login'))
                                <a class="text-link" href="{{ route('login') }}">ya tengo cuenta</a>
                            @endif
                        @else
                            <a class="button button-ink button-large" href="{{ route('dashboard') }}">volver a mi garaje <span aria-hidden="true">→</span></a>
                        @endguest
                    </div>
                    <p class="hero-note"><span aria-hidden="true">✳</span> Sin conectar al coche. Sin letra pequeña. Tus datos, a tu ritmo.</p>
                </div>

                <div class="hero-art">
                    <span class="art-caption art-caption-top">arcano nº 01 <span>—</span> el camino</span>
                    <button class="tarot-card" type="button" aria-pressed="false" aria-label="Girar la carta de ejemplo para ver los datos del vehículo">
                        <span class="tarot-card-inner">
                            <span class="tarot-face tarot-front">
                                <span class="tarot-border">
                                    <span class="tarot-number">I</span>
                                    <span class="tarot-illustration" aria-hidden="true">
                                        <svg viewBox="0 0 300 270" role="img" aria-label="Ilustración lineal de un coche clásico">
                                            <path d="M45 166h210l-13-48c-3-11-10-18-22-22l-39-14H113l-29 21c-9 7-15 16-19 28l-20 35Z" />
                                            <path d="M80 135h145M111 82l-20 51m76-51 6 51M44 166l-5 26h17m200-26 5 26h-17" />
                                            <path d="M92 192a20 20 0 1 0 40 0m71 0a20 20 0 1 0 40 0" />
                                            <circle cx="112" cy="192" r="7" /><circle cx="223" cy="192" r="7" />
                                            <path d="M58 153h17m154 0h15M145 59v-17m-12 5h24M60 218h180" />
                                            <path d="M73 227h16m122 0h16" />
                                        </svg>
                                    </span>
                                    <span class="tarot-sun" aria-hidden="true">✳</span>
                                    <span class="tarot-name">el viajero</span>
                                    <span class="tarot-subtitle">SEAT 600 · 1972</span>
                                    <span class="tarot-index">GARAGE · MMXXVI</span>
                                </span>
                            </span>
                            <span class="tarot-face tarot-back" aria-hidden="true">
                                <span class="tarot-border tarot-back-border">
                                    <span class="tarot-number">I</span>
                                    <span class="back-ornament">✳</span>
                                    <span class="back-data"><small>matrícula</small><strong>1234 GRC</strong></span>
                                    <span class="back-data"><small>kilometraje</small><strong>84.260 km</strong></span>
                                    <span class="back-data"><small>próxima ITV</small><strong>14 · 06 · 2027</strong></span>
                                    <span class="tarot-index">toca para volver</span>
                                </span>
                            </span>
                        </span>
                    </button>
                    <span class="art-caption art-caption-bottom">toca la carta para darle la vuelta <span>↗</span></span>
                    <span class="hero-side-note">una carta por cada vehículo</span>
                </div>
            </section>

            <section class="proof-strip" aria-label="Resumen de funciones">
                <p><span class="proof-number">01</span><span>avisos por fecha<br>o por kilometraje</span></p>
                <p><span class="proof-number">02</span><span>historial y gasto<br>por vehículo</span></p>
                <p><span class="proof-number">03</span><span>una guía para<br>tus dudas mecánicas</span></p>
            </section>

            <section class="manifesto" id="como-funciona">
                <div class="manifesto-heading">
                    <p class="eyebrow"><span class="eyebrow-rule"></span> una herramienta, no un truco</p>
                    <h2>Primero, lo importante.<br><em>Lo demás, que te acompañe.</em></h2>
                </div>
                <div class="manifesto-body">
                    <p>Garagic te ayuda a recordar lo que necesita tu vehículo y a entender cuánto cuesta mantenerlo. La carta, la vitalidad y los sellos hacen más agradable llevar la cuenta; no sustituyen el mantenimiento real.</p>
                    <a class="text-link" href="#cuaderno">ver qué guarda el cuaderno <span aria-hidden="true">↓</span></a>
                </div>
            </section>

            <section class="notebook" id="cuaderno" aria-label="Lo que puedes llevar al día">
                <div class="notebook-label"><span>del cuaderno</span><span>pág. 01 — 03</span></div>
                <article class="notebook-entry">
                    <span class="entry-index">A</span>
                    <div><h3>Lo que toca</h3><p>Avisos de ITV, seguro, aceite y ruedas. Por fecha o por kilómetros, con el estado escrito en claro.</p></div>
                    <span class="entry-mark" aria-hidden="true">↗</span>
                </article>
                <article class="notebook-entry">
                    <span class="entry-index">B</span>
                    <div><h3>Lo que ya hiciste</h3><p>Un historial de mantenimientos y gastos que puedes consultar cuando lo necesites.</p></div>
                    <span class="entry-mark" aria-hidden="true">↗</span>
                </article>
                <article class="notebook-entry">
                    <span class="entry-index">C</span>
                    <div><h3>Lo que no sabes</h3><p>El Oráculo puede orientarte sobre síntomas mecánicos. Es una ayuda, no un diagnóstico profesional.</p></div>
                    <span class="ai-seal">IA</span>
                </article>
            </section>

            <section class="closing-note">
                <span class="closing-star" aria-hidden="true">✳</span>
                <p>Un buen viaje empieza<br><em>por saber cómo está el coche.</em></p>
                @guest
                    @if (Route::has('register'))
                        <a class="button button-ink" href="{{ route('register') }}">crear mi cuenta <span aria-hidden="true">→</span></a>
                    @endif
                @endguest
            </section>
        </main>

        <footer class="site-footer">
            <a class="wordmark wordmark-small" href="{{ route('home') }}"><span class="wordmark-mark" aria-hidden="true">G</span><span>garagic</span></a>
            <p>hecho para cuidar lo que te lleva</p>
            <span>© garagic · 2026</span>
        </footer>
    </body>
</html>
