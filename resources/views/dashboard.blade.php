<x-layouts::app :title="__('Dashboard')">
    <main class="garage-page">
        <header class="garage-heading">
            <div>
                <p class="eyebrow"><span class="eyebrow-rule"></span> tu cuaderno · lunes, 12 de octubre de 2026</p>
                <h1>El garaje <em>de David.</em></h1>
                <p class="garage-intro">Un vistazo a tus vehículos y a lo que conviene atender.</p>
            </div>
            <a class="button button-ink" href="#vehiculos">ver mis vehículos <span aria-hidden="true">↓</span></a>
        </header>

        <section class="garage-summary" aria-label="Resumen de avisos">
            <div class="summary-cell"><span class="summary-label">vehículos</span><strong>04</strong><small>en tu cuaderno</small></div>
            <div class="summary-cell"><span class="summary-label">por atender</span><strong>02</strong><small>un aviso próximo y uno vencido</small></div>
            <div class="summary-cell"><span class="summary-label">gasto este año</span><strong class="summary-money">684,50 €</strong><small>en todos tus vehículos</small></div>
        </section>

        <section class="garage-section" id="vehiculos">
            <div class="section-heading"><div><p class="eyebrow">la baraja</p><h2>Tus vehículos</h2></div><span class="section-aside">4 cartas · 1 aviso pendiente</span></div>
            <div class="vehicle-grid">
                <article class="vehicle-entry">
                    <a class="vehicle-card-link" href="#vehiculo-uno" aria-label="Ver ficha del Seat León">
                        <div class="vehicle-card vehicle-card-clean"><span class="vehicle-card-number">VII</span><span class="vehicle-card-art" aria-hidden="true">✳</span><span class="vehicle-card-title">el guardián</span><span class="vehicle-card-name">SEAT LEÓN · 2019</span><span class="vehicle-card-plate">1234 KLM</span></div>
                    </a>
                    <div class="vehicle-vitality"><div class="vitality-line"><span>vitalidad</span><strong>95<small> / 100</small></strong></div><div class="vitality-track" role="meter" aria-label="Vitalidad 95 de 100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="95"><span style="--vitality: 95%"></span></div><p>al día · última revisión hace 2 meses</p></div>
                </article>
                <article class="vehicle-entry">
                    <a class="vehicle-card-link" href="#vehiculo-dos" aria-label="Ver ficha de la Honda CB500">
                        <div class="vehicle-card vehicle-card-worn"><span class="vehicle-card-number">XII</span><span class="vehicle-card-art vehicle-moto" aria-hidden="true">⌁</span><span class="vehicle-card-title">la rueda</span><span class="vehicle-card-name">HONDA CB500 · 2016</span><span class="vehicle-card-plate">5678 JKL</span></div>
                    </a>
                    <div class="vehicle-vitality"><div class="vitality-line"><span>vitalidad</span><strong>72<small> / 100</small></strong></div><div class="vitality-track" role="meter" aria-label="Vitalidad 72 de 100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="72"><span style="--vitality: 72%"></span></div><p>revisar pronto · seguro en 18 días</p></div>
                </article>
                <article class="vehicle-entry">
                    <a class="vehicle-card-link" href="#vehiculo-tres" aria-label="Ver ficha del Seat 600">
                        <div class="vehicle-card vehicle-card-worn vehicle-card-low"><span class="vehicle-card-number">XVI</span><span class="vehicle-card-art vehicle-car-low" aria-hidden="true">⌁</span><span class="vehicle-card-title">la torre</span><span class="vehicle-card-name">SEAT 600 · 1972</span><span class="vehicle-card-plate">4321 BCD</span></div>
                    </a>
                    <div class="vehicle-vitality"><div class="vitality-line"><span>vitalidad</span><strong>30<small> / 100</small></strong></div><div class="vitality-track" role="meter" aria-label="Vitalidad 30 de 100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="30"><span style="--vitality: 30%"></span></div><p>varios avisos vencidos</p></div>
                </article>
                <article class="vehicle-entry">
                    <a class="vehicle-card-link" href="#vehiculo-cuatro" aria-label="Ver ficha de la furgoneta Volkswagen">
                        <div class="vehicle-card vehicle-card-empty"><span class="vehicle-card-number">0</span><span class="vehicle-card-art vehicle-van" aria-hidden="true">◇</span><span class="vehicle-card-title">la viajera</span><span class="vehicle-card-name">VW TRANSPORTER · 2008</span><span class="vehicle-card-plate">9012 GHT</span></div>
                    </a>
                    <div class="vehicle-vitality"><div class="vitality-line"><span>vitalidad</span><strong>sin datos</strong></div><div class="vitality-track vitality-track-empty"><span></span></div><p>registra el primer mantenimiento</p></div>
                </article>
            </div>
        </section>

        <section class="garage-lower">
            <div class="upcoming-panel">
                <div class="section-heading"><div><p class="eyebrow">próximas señales</p><h2>Lo que toca</h2></div><a class="text-link" href="#avisos">ver avisos →</a></div>
                <div class="reminder-row reminder-overdue"><span class="reminder-date">02 OCT</span><div><strong>ITV · Seat León</strong><small>venció hace 10 días</small></div><span class="reminder-state">vencida</span></div>
                <div class="reminder-row"><span class="reminder-date">30 OCT</span><div><strong>Seguro · Honda CB500</strong><small>faltan 18 días</small></div><span class="reminder-state">próxima</span></div>
                <p class="demo-note">Datos de ejemplo para el prototipo.</p>
            </div>
            <aside class="oracle-note">
                <p class="eyebrow"><span class="ai-seal">IA</span> el oráculo</p>
                <h2>Una duda mecánica<br><em>también tiene sitio.</em></h2>
                <p>Describe un síntoma y recibe orientación sobre posibles causas y urgencia. No sustituye a un profesional.</p>
                <a class="text-link" href="#oraculo">consultar al Oráculo <span aria-hidden="true">→</span></a>
            </aside>
        </section>
    </main>
</x-layouts::app>
