<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webmarko — Agence Digitale</title>
    <meta name="description" content="Webmarko, votre agence digitale spécialisée en SEO, campagnes Ads, réseaux sociaux et branding.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #f59e0b;
            --primary-dark: #d97706;
            --bg-dark: #0f0f0f;
            --bg-card: #1a1a1a;
            --bg-card2: #222222;
            --text-main: #f5f5f0;
            --text-muted: #888;
            --border: rgba(255,255,255,0.08);
            --radius: 16px;
        }

        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-main); min-height: 100vh; }

        /* ---- NAV ---- */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.1rem 4rem;
            background: rgba(15,15,15,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }
        .nav-brand { font-size: 1.4rem; font-weight: 800; color: var(--primary); letter-spacing: -0.5px; }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color .2s; }
        .nav-links a:hover { color: var(--text-main); }
        .nav-cta {
            background: var(--primary); color: #0f0f0f; padding: .55rem 1.4rem;
            border-radius: 8px; font-weight: 700; font-size: 0.875rem;
            text-decoration: none; transition: background .2s, transform .15s;
        }
        .nav-cta:hover { background: var(--primary-dark); transform: translateY(-1px); }

        /* ---- HERO ---- */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 8rem 2rem 4rem;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 30%, rgba(245,158,11,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: .5rem;
            background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3);
            color: var(--primary); padding: .35rem 1rem; border-radius: 999px;
            font-size: 0.8rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase;
            margin-bottom: 2rem;
            animation: fadeInDown .6s ease both;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800; line-height: 1.1; letter-spacing: -1.5px;
            margin-bottom: 1.5rem;
            animation: fadeInUp .7s ease .1s both;
        }
        .hero h1 span { color: var(--primary); }
        .hero p {
            max-width: 560px; margin: 0 auto 2.5rem;
            color: var(--text-muted); font-size: 1.1rem; line-height: 1.7;
            animation: fadeInUp .7s ease .2s both;
        }
        .hero-actions {
            display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;
            animation: fadeInUp .7s ease .3s both;
        }
        .btn-primary {
            background: var(--primary); color: #0f0f0f; padding: .85rem 2rem;
            border-radius: 10px; font-weight: 700; font-size: 1rem;
            text-decoration: none; transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 24px rgba(245,158,11,0.3);
        }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 8px 32px rgba(245,158,11,0.4); }
        .btn-secondary {
            background: transparent; color: var(--text-main); padding: .85rem 2rem;
            border-radius: 10px; font-weight: 600; font-size: 1rem;
            text-decoration: none; border: 1px solid var(--border);
            transition: border-color .2s, background .2s;
        }
        .btn-secondary:hover { border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.04); }

        /* ---- STATS ---- */
        .stats {
            display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap;
            padding: 3rem 4rem;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .stat-item { text-align: center; }
        .stat-item .num { font-size: 2.2rem; font-weight: 800; color: var(--primary); }
        .stat-item .label { font-size: 0.85rem; color: var(--text-muted); margin-top: .2rem; }

        /* ---- SERVICES ---- */
        .section { padding: 5rem 4rem; max-width: 1200px; margin: 0 auto; }
        .section-label {
            font-size: 0.75rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: var(--primary); margin-bottom: 1rem;
        }
        .section-title { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; letter-spacing: -1px; margin-bottom: 1rem; }
        .section-sub { color: var(--text-muted); font-size: 1rem; max-width: 500px; line-height: 1.7; margin-bottom: 3rem; }

        .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
        .service-card {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 2rem; transition: transform .25s, border-color .25s, box-shadow .25s;
        }
        .service-card:hover { transform: translateY(-4px); border-color: rgba(245,158,11,0.3); box-shadow: 0 12px 40px rgba(0,0,0,0.3); }
        .service-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: rgba(245,158,11,0.12); display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1.2rem;
        }
        .service-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: .5rem; }
        .service-card p { color: var(--text-muted); font-size: 0.875rem; line-height: 1.6; }

        /* ---- WHY US ---- */
        .why-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .why-card {
            background: var(--bg-card2); border: 1px solid var(--border); border-radius: 12px;
            padding: 1.5rem; display: flex; flex-direction: column; gap: .5rem;
        }
        .why-card .icon { font-size: 1.4rem; }
        .why-card h4 { font-weight: 700; font-size: 0.95rem; }
        .why-card p { color: var(--text-muted); font-size: 0.82rem; line-height: 1.5; }

        /* ---- CTA SECTION ---- */
        .cta-section {
            background: linear-gradient(135deg, rgba(245,158,11,0.1) 0%, rgba(245,158,11,0.03) 100%);
            border: 1px solid rgba(245,158,11,0.2);
            border-radius: 24px; padding: 4rem; text-align: center;
            margin: 2rem 4rem 5rem;
        }
        .cta-section h2 { font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 800; margin-bottom: 1rem; }
        .cta-section p { color: var(--text-muted); margin-bottom: 2rem; }

        /* ---- FOOTER ---- */
        footer {
            border-top: 1px solid var(--border);
            padding: 2rem 4rem;
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }
        footer .brand { font-weight: 800; color: var(--primary); font-size: 1.1rem; }
        footer .links { display: flex; gap: 1.5rem; }
        footer .links a { color: var(--text-muted); text-decoration: none; font-size: 0.85rem; transition: color .2s; }
        footer .links a:hover { color: var(--text-main); }
        footer .copy { color: var(--text-muted); font-size: 0.8rem; }

        /* ---- ANIMATIONS ---- */
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            nav { padding: 1rem 1.5rem; }
            .nav-links { gap: 1rem; }
            .section { padding: 4rem 1.5rem; }
            .stats { padding: 2rem 1.5rem; gap: 2rem; }
            .cta-section { margin: 2rem 1.5rem; padding: 2.5rem 1.5rem; }
            footer { padding: 1.5rem; flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav>
        <span class="nav-brand">Webmarko</span>
        <div class="nav-links">
            <a href="#services">Services</a>
            <a href="#pourquoi">Pourquoi nous</a>
            @if(Route::has('login'))
                @auth
                    <a href="/admin" class="nav-cta">Tableau de bord</a>
                @else
                    <a href="/admin/login" class="nav-cta">Connexion</a>
                @endauth
            @endif
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero">
        <div>
            <div class="hero-badge">✦ Agence Digitale</div>
            <h1>Propulsez votre<br>activité <span>en ligne</span></h1>
            <p>Nous concevons des stratégies digitales percutantes — SEO, publicité, réseaux sociaux, branding — pour faire croître votre entreprise.</p>
            <div class="hero-actions">
                <a href="#services" class="btn-primary">Découvrir nos services</a>
                <a href="/admin/login" class="btn-secondary">Espace client →</a>
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <div class="stats">
        <div class="stat-item"><div class="num">+120</div><div class="label">Clients accompagnés</div></div>
        <div class="stat-item"><div class="num">+350</div><div class="label">Campagnes lancées</div></div>
        <div class="stat-item"><div class="num">98%</div><div class="label">Taux de satisfaction</div></div>
        <div class="stat-item"><div class="num">5 ans</div><div class="label">D'expertise digitale</div></div>
    </div>

    {{-- SERVICES --}}
    <section class="section" id="services">
        <div class="section-label">Ce que nous faisons</div>
        <h2 class="section-title">Nos services</h2>
        <p class="section-sub">Des solutions complètes pour votre présence digitale, adaptées à chaque objectif.</p>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">🔍</div>
                <h3>SEO & Référencement</h3>
                <p>Optimisation technique et éditoriale pour positionner votre site en tête des résultats Google.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">📢</div>
                <h3>Publicité Ads</h3>
                <p>Campagnes Google Ads et Meta Ads ciblées pour maximiser votre retour sur investissement.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">📱</div>
                <h3>Social Media</h3>
                <p>Gestion éditoriale et community management pour engager votre audience sur tous les réseaux.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">🎨</div>
                <h3>Branding & Design</h3>
                <p>Identité visuelle forte et cohérente qui reflète vos valeurs et marque les esprits.</p>
            </div>
        </div>
    </section>

    {{-- POURQUOI NOUS --}}
    <section class="section" id="pourquoi" style="padding-top:0">
        <div class="section-label">Nos atouts</div>
        <h2 class="section-title">Pourquoi Webmarko ?</h2>
        <p class="section-sub">Une équipe engagée, des résultats mesurables, une transparence totale.</p>
        <div class="why-grid">
            <div class="why-card">
                <div class="icon">📊</div>
                <h4>Reporting détaillé</h4>
                <p>Accédez à votre espace client pour suivre vos campagnes en temps réel.</p>
            </div>
            <div class="why-card">
                <div class="icon">⚡</div>
                <h4>Réactivité</h4>
                <p>Une équipe disponible et des délais de réponse rapides pour vos demandes.</p>
            </div>
            <div class="why-card">
                <div class="icon">🎯</div>
                <h4>Stratégie personnalisée</h4>
                <p>Chaque client a sa propre stratégie, adaptée à son secteur et ses objectifs.</p>
            </div>
            <div class="why-card">
                <div class="icon">🔒</div>
                <h4>Transparence totale</h4>
                <p>Accès à vos factures, campagnes et données, à tout moment.</p>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <div class="cta-section">
        <h2>Prêt à passer à la vitesse supérieure ?</h2>
        <p>Rejoignez nos clients et boostez votre visibilité digitale dès aujourd'hui.</p>
        <a href="/admin/login" class="btn-primary">Accéder à mon espace →</a>
    </div>

    {{-- FOOTER --}}
    <footer>
        <span class="brand">Webmarko</span>
        <div class="links">
            <a href="#services">Services</a>
            <a href="/admin/login">Connexion Admin</a>
            <a href="/client/login">Portail client</a>
        </div>
        <span class="copy">© {{ date('Y') }} Webmarko. Tous droits réservés.</span>
    </footer>

</body>
</html>
