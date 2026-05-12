<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sport+ | Play. Book. Win.</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="/sport_plus/public/css/style.css"/>
  <style>
    .home-hero {
      position: relative !important;
      display: block !important;
      height: 100vh !important;
      min-height: 760px !important;
      padding: 0 !important;
      overflow: hidden !important;
      background: #050505 !important;
    }
    .home-hero::before {
      content: "";
      position: absolute;
      inset: 72px 0 0;
      z-index: 2;
      pointer-events: none;
      background: linear-gradient(90deg, #050505 0%, rgba(5,5,5,.94) 23%, rgba(5,5,5,.48) 48%, rgba(5,5,5,.18) 100%);
    }
    .home-hero .hero-left {
      position: absolute !important;
      z-index: 4 !important;
      left: clamp(54px, 6vw, 130px) !important;
      top: 50% !important;
      width: 360px !important;
      transform: translateY(-46%) !important;
      padding: 0 !important;
      background: transparent !important;
    }
    .home-hero .hero-title {
      font-family: var(--font-display) !important;
      font-size: clamp(86px, 8.5vw, 142px) !important;
      line-height: .88 !important;
      letter-spacing: 0 !important;
      margin: 0 !important;
      color: #fff !important;
    }
    .home-hero .hero-title span { display: block !important; }
    .home-hero .hero-title .green { color: var(--green) !important; }
    .home-hero .hero-sub {
      max-width: 330px !important;
      margin: 28px 0 34px !important;
      color: rgba(255,255,255,.78) !important;
      font-size: 18px !important;
      line-height: 1.45 !important;
    }
    .home-hero .hero-right {
      position: absolute !important;
      z-index: 1 !important;
      top: 92px !important;
      right: 80px !important;
      bottom: 28px !important;
      left: 34% !important;
      width: auto !important;
      height: auto !important;
      padding: 0 !important;
      display: block !important;
      overflow: visible !important;
    }
    .home-hero .hero-img-grid {
      width: 100% !important;
      height: 100% !important;
      display: grid !important;
      grid-template-columns: 1fr 1fr !important;
      grid-template-rows: 1fr 1fr !important;
      gap: 14px !important;
      transform: skewX(-7deg) !important;
      transform-origin: center !important;
      overflow: visible !important;
    }
    .home-hero .hero-img-item {
      position: relative !important;
      min-width: 0 !important;
      min-height: 0 !important;
      overflow: hidden !important;
      border-radius: 18px !important;
      background: #151515 !important;
    }
    .home-hero .hero-panel {
      width: 124% !important;
      height: 100% !important;
      margin-left: -12% !important;
      background-size: cover !important;
      background-position: center !important;
      transform: skewX(7deg) scale(1.08) !important;
      filter: brightness(.5) saturate(.95) !important;
    }
    .home-hero .hero-footer {
      position: absolute !important;
      z-index: 5 !important;
      left: 48px !important;
      right: 48px !important;
      bottom: 32px !important;
      padding: 0 !important;
    }
    .cities-section {
      background: #070707 !important;
      padding: 92px 60px 84px !important;
    }
    .cities-section .label-tag,
    .cities-section .section-title {
      text-align: center !important;
    }
    .cities-grid {
      display: grid !important;
      grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
      gap: 24px !important;
      max-width: 1180px !important;
      margin: 36px auto 0 !important;
    }
    .city-card {
      display: block !important;
      position: relative !important;
      height: 310px !important;
      overflow: hidden !important;
      border-radius: 20px !important;
      border: 1px solid rgba(255,255,255,.08) !important;
      background: #161616 !important;
      transition: transform .28s ease, border-color .28s ease, box-shadow .28s ease !important;
    }
    .city-card:hover {
      transform: translateY(-8px) !important;
      border-color: rgba(76,255,114,.45) !important;
      box-shadow: 0 20px 42px rgba(0,0,0,.38), 0 0 0 1px rgba(76,255,114,.08) !important;
    }
    .city-img {
      position: absolute !important;
      inset: 0 !important;
      background-size: cover !important;
      background-position: center !important;
      transform: scale(1.01) !important;
      transition: transform .55s ease, filter .35s ease !important;
      filter: brightness(.78) saturate(.95) !important;
    }
    .city-card:hover .city-img {
      transform: scale(1.1) !important;
      filter: brightness(.94) saturate(1.08) !important;
    }
    .city-overlay {
      position: absolute !important;
      inset: 0 !important;
      background: linear-gradient(to top, rgba(0,0,0,.92), rgba(0,0,0,.22), rgba(0,0,0,.05)) !important;
      transition: background .35s ease !important;
    }
    .city-card:hover .city-overlay {
      background: linear-gradient(to top, rgba(0,0,0,.82), rgba(0,0,0,.12), rgba(0,0,0,0)) !important;
    }
    .city-info {
      position: absolute !important;
      z-index: 2 !important;
      left: 24px !important;
      right: 24px !important;
      bottom: 24px !important;
    }
    .city-info h3 {
      font-family: var(--font-display) !important;
      font-size: 42px !important;
      line-height: 1 !important;
      margin: 0 0 8px !important;
      transition: color .25s ease, transform .25s ease !important;
    }
    .city-card:hover .city-info h3 {
      color: var(--green) !important;
      transform: translateY(-2px) !important;
    }
    .city-count {
      display: block !important;
      color: rgba(255,255,255,.74) !important;
      font-size: 14px !important;
      line-height: 1.45 !important;
    }
    @media (max-width: 900px) {
      .home-hero { height: auto !important; min-height: 100vh !important; padding: 120px 18px 100px !important; }
      .home-hero .hero-left { position: relative !important; left: auto !important; top: auto !important; transform: none !important; width: auto !important; margin-bottom: 32px !important; }
      .home-hero .hero-right { position: relative !important; left: auto !important; right: auto !important; top: auto !important; bottom: auto !important; height: 430px !important; }
      .home-hero .hero-img-grid { transform: none !important; }
      .home-hero .hero-panel { width: 100% !important; margin-left: 0 !important; transform: none !important; }
      .cities-grid { grid-template-columns: 1fr !important; }
    }
  </style>
</head>
<body>

<nav class="navbar">
  <a href="/sport_plus/" class="logo">SPORT<span>+</span></a>
  <ul class="nav-links">
    <li><a href="/sport_plus/" class="active">Home</a></li>
    <li><a href="/sport_plus/terrains">Terrains</a></li>
    <li><a href="#about-us">About Us</a></li>
  </ul>
  <div class="nav-actions">
    <a href="/sport_plus/login" class="btn-ghost">Login</a>
    <a href="/sport_plus/terrains" class="btn-green">Book Now</a>
  </div>
  <button class="hamburger" onclick="toggleMenu()">&#9776;</button>
</nav>

<section class="hero home-hero">
  <div class="hero-left">
    <h1 class="hero-title">
      <span>PLAY.</span>
      <span>BOOK.</span>
      <span class="green">WIN.</span>
    </h1>
    <p class="hero-sub">Find and book the best sports terrains near you.</p>
    <a href="/sport_plus/terrains" class="btn-green large">Start Booking <span class="arrow">→</span></a>
  </div>
  <div class="hero-right">
    <div class="hero-img-grid">
      <div class="hero-img-item hero-img-wide">
        <div class="hero-panel" style="background-image:url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1200&q=80')"></div>
      </div>
      <div class="hero-img-item">
        <div class="hero-panel" style="background-image:url('https://images.unsplash.com/photo-1519861531473-9200262188bf?auto=format&fit=crop&w=1200&q=80')"></div>
      </div>
      <div class="hero-img-item">
        <div class="hero-panel" style="background-image:url('https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?auto=format&fit=crop&w=1200&q=80')"></div>
      </div>
      <div class="hero-img-item">
        <div class="hero-panel" style="background-image:url('https://i.pinimg.com/1200x/b7/55/1d/b7551dab633bdd6d06cba6bb713bf628.jpg')"></div>
      </div>
    </div>
  </div>
  
</section>

<section class="cities-section">
  <p class="label-tag center">CITIES</p>
  <h2 class="section-title center">Book Across Morocco</h2>
  <div class="cities-grid">
    <a href="/sport_plus/terrains?city=Tangier" class="city-card">
      <div class="city-img" style="background-image:url('https://i.pinimg.com/736x/87/55/85/875585748dd5ff5656424f63fe0b9c80.jpg')"></div>
      <div class="city-overlay"></div>
      <div class="city-info">
        <h3>Tangier</h3>
        <span class="city-count">Football, tennis, padel, basketball</span>
      </div>
    </a>
    <a href="/sport_plus/terrains?city=Marrakesh" class="city-card">
      <div class="city-img" style="background-image:url('https://i.pinimg.com/1200x/ab/02/44/ab024490d6f080530c0da81877240166.jpg')"></div>
      <div class="city-overlay"></div>
      <div class="city-info">
        <h3>Marrakesh</h3>
        <span class="city-count">Premium clubs and community courts</span>
      </div>
    </a>
    <a href="/sport_plus/terrains?city=Casablanca" class="city-card">
      <div class="city-img" style="background-image:url('https://i.pinimg.com/1200x/06/cf/a9/06cfa968d4345204588f16d8593001b6.jpg')"></div>
      <div class="city-overlay"></div>
      <div class="city-info">
        <h3>Casablanca</h3>
        <span class="city-count">Urban pitches and indoor arenas</span>
      </div>
    </a>
  </div>
</section>

<section class="about-section" id="about-us">
  <div class="about-left">
    <p class="label-tag">ABOUT US</p>
    <h2>We're more than<br/>just a booking platform.</h2>
    <p class="about-desc">We connect players with places. Our mission is to make sports more accessible, enjoyable, and part of your everyday life in Tangier, Marrakesh, and Casablanca.</p>
    <a href="/sport_plus/about" class="btn-outline">Learn More <span>→</span></a>
  </div>
  <div class="about-cards">
    <div class="about-card">
      <div class="card-img" style="background:url('https://images.unsplash.com/photo-1459865264687-595d652de67e?w=400&q=80') center/cover"></div>
      <div class="card-icon">⚽</div>
      <h3>Quality Terrains</h3>
      <p>Handpicked terrains that meet the highest standards for your game.</p>
    </div>
    <div class="about-card">
      <div class="card-img" style="background:url('https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=400&q=80') center/cover"></div>
      <div class="card-icon">📅</div>
      <h3>Easy Booking</h3>
      <p>Book in seconds, choose your time, and focus on what matters.</p>
    </div>
    <div class="about-card">
      <div class="card-img" style="background:url('https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=400&q=80') center/cover"></div>
      <div class="card-icon">👥</div>
      <h3>Built for Everyone</h3>
      <p>Whether you play casually or competitively, we're here for you.</p>
    </div>
  </div>
</section>

<section class="sports-section">
  <p class="label-tag center">SPORTS</p>
  <h2 class="section-title center">Find Your Game</h2>
  <div class="sport-pills">
    <button class="pill active">All</button>
    <button class="pill">Tangier</button>
    <button class="pill">Marrakesh</button>
    <button class="pill">Casablanca</button>
    <button class="pill">⚽ Football</button>
    <button class="pill">🎾 Tennis</button>
    <button class="pill">🏓 Padel</button>
    <button class="pill">🏀 Basketball</button>
  </div>
  <div class="terrains-grid">
    <?php
      require_once __DIR__ . '/../config/sport_images.php';
      $_hCityBadge = ['marrakesh'=>'marrakesh','casablanca'=>'casa'];
      $_hSportTag  = ['tennis'=>'tennis','padel'=>'padel','basketball'=>'basketball'];
      $homeTerrains = array_slice($terrains ?? [], 0, 9);
      foreach ($homeTerrains as $t):
        $hSport   = $t['type_sport'] ?? 'Football';
        $hCity    = $t['localisation'] ?? '';
        $hCityKey = function_exists('_cityKey') ? _cityKey($hCity) : strtolower($hCity);
        $hSKey    = strtolower($hSport);
        $hImg     = sportImage($hSport);
        $hBadge   = $_hCityBadge[$hCityKey] ?? '';
        $hTag     = $_hSportTag[$hSKey]     ?? '';
    ?>
    <div class="terrain-card filter-card" data-city="<?= $hCityKey ?>" data-sport="<?= $hSKey ?>">
      <div class="terrain-img" style="background:url('<?= $hImg ?>') center/cover"></div>
      <div class="terrain-body">
        <div class="terrain-tag-row"><span class="sport-tag <?= $hTag ?>"><?= e($hSport) ?></span><span class="city-badge <?= $hBadge ?>"><?= e($hCity) ?></span></div>
        <h3><?= e($t['nom']) ?></h3>
        <p class="terrain-loc">📍 <?= e($hCity) ?></p>
        <div class="terrain-footer">
          <span class="price"><?= e($t['prix']) ?> MAD<small>/h</small></span>
          <a href="/sport_plus/terrain/<?= (int)$t['id'] ?>" class="btn-green small">Book</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="center-btn">
    <a href="/sport_plus/terrains" class="btn-outline">View All Terrains <span>→</span></a>
  </div>
</section>

<section class="community-section">
  <p class="label-tag center">FOLLOW US</p>
  <h2 class="section-title center">Join our community</h2>
  <div class="community-grid">
    <div class="comm-img" style="background:url('https://i.pinimg.com/1200x/37/73/ed/3773ed1e01ec77daa086c5b9d582f4ef.jpg') center/cover"></div>
    <div class="comm-img" style="background:url('https://i.pinimg.com/1200x/40/c9/96/40c996465fc444f90c969779e2d49398.jpg') center/cover"></div>
    <div class="comm-img" style="background:url('https://i.pinimg.com/1200x/62/54/a7/6254a7f68d755d97d15e49b1531ca891.jpg') center/cover"></div>
    <div class="comm-img" style="background:url('https://i.pinimg.com/1200x/31/8d/ad/318dad6e323a680bfdfc3f95bee45035.jpg') center/cover"></div>
    <div class="comm-img" style="background:url('https://i.pinimg.com/736x/b0/85/ec/b085ecd10d5a0d90dab7c0c571daa087.jpg') center/cover"></div>
    <div class="comm-img" style="background:url('https://i.pinimg.com/1200x/78/92/73/78927311e32410c75439fb31a3301dbc.jpg') center/cover"></div>
  </div>
  <div class="social-row">
    <span class="handle"><span class="dot"></span> @sportplus_official</span>
    <div class="icons">
      <i class="fa-brands fa-instagram"></i>
      <i class="fa-brands fa-tiktok"></i>
      <i class="fa-brands fa-x-twitter"></i>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="footer-top">
    <div class="footer-brand">
      <span class="logo">SPORT<span>+</span></span>
      <p>Making sports accessible for everyone, everywhere.</p>
    </div>
    <div class="footer-col">
      <h4>Explore</h4>
      <a href="/sport_plus/terrains">Terrains</a>
      <a href="#about-us">About Us</a>
    </div>
    <div class="footer-col">
      <h4>Support</h4>
      <a href="#">FAQ</a>
      <a href="#">Terms & Conditions</a>
      <a href="#">Privacy Policy</a>
    </div>
    <div class="footer-col newsletter">
      <h4>Newsletter</h4>
      <p>Get updates about new terrains and exclusive offers.</p>
      <div class="newsletter-form">
        <input type="email" placeholder="Your email"/>
        <button class="btn-green small">→</button>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 Sport+. All rights reserved.</p>
  </div>
</footer>

<script>
  function toggleMenu() {
    document.querySelector('.nav-links').classList.toggle('open');
  }

  const activeFilters = { city: 'all', sport: 'all' };
  document.querySelectorAll('.pill').forEach(p => {
    p.addEventListener('click', () => {
      const text = p.textContent.toLowerCase();
      const group = ['tangier', 'marrakesh', 'casablanca'].includes(text) ? 'city' : 'sport';
      activeFilters[group] = text.includes('all') ? 'all' : text.replace(/[^a-z]/g, '');
      p.closest('.sport-pills').querySelectorAll('.pill').forEach(x => {
        const xText = x.textContent.toLowerCase();
        const xGroup = ['tangier', 'marrakesh', 'casablanca'].includes(xText) ? 'city' : 'sport';
        if (xGroup === group) x.classList.remove('active');
      });
      p.classList.add('active');
      document.querySelectorAll('.filter-card').forEach(card => {
        const cityOk = activeFilters.city === 'all' || card.dataset.city === activeFilters.city;
        const sportOk = activeFilters.sport === 'all' || card.dataset.sport === activeFilters.sport;
        card.style.display = cityOk && sportOk ? '' : 'none';
      });
    });
  });
</script>
</body>
</html>
