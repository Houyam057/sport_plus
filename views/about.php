<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sport+ | About Us</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/sport_plus/public/css/style.css"/>
  <style>
    .about-hero { min-height: 78vh; padding: 150px 60px 80px; background: linear-gradient(90deg, rgba(0,0,0,.86), rgba(0,0,0,.2)), url('https://images.unsplash.com/photo-1553778263-73a83bab9b0c?w=1400&q=85') center/cover; display:flex; align-items:flex-end; position:relative; }
    .about-hero h1 { font-family: var(--font-display); font-size: clamp(72px, 10vw, 142px); line-height:.9; max-width: 720px; }
    .about-hero h1 span { color: var(--green); }
    .floating-stat { position:absolute; right:60px; bottom:70px; background:rgba(10,10,10,.82); border:1px solid rgba(255,255,255,.12); border-radius:var(--radius); padding:20px 26px; min-width:180px; }
    .floating-stat strong { font-family:var(--font-display); color:var(--green); font-size:48px; line-height:1; display:block; }
    .stats-band { display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:rgba(255,255,255,.06); }
    .stats-band div { background:var(--bg2); padding:34px; text-align:center; }
    .stats-band strong { font-family:var(--font-display); color:var(--green); font-size:44px; line-height:1; display:block; }
    .mission-grid { padding:90px 60px; background:var(--bg); display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
    .mission-card { background:var(--card); border:1px solid rgba(255,255,255,.06); border-radius:var(--radius); padding:26px; }
    .mission-card span { color:var(--green); font-size:28px; }
    .mission-card h3 { margin:16px 0 8px; }
    .mission-card p { color:var(--gray); font-size:14px; line-height:1.7; }
    .values-section { padding:90px 60px; background:var(--bg2); display:grid; grid-template-columns:360px 1fr; gap:60px; }
    .value-list { display:grid; gap:18px; }
    .value-item { display:grid; grid-template-columns:56px 1fr; gap:18px; align-items:start; background:var(--card); border:1px solid rgba(255,255,255,.06); border-radius:var(--radius); padding:22px; }
    .value-item strong:first-child { font-family:var(--font-display); color:var(--green); font-size:34px; }
    .team-grid { padding:90px 60px; background:var(--bg); display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
    .team-card { background:var(--card); border:1px solid rgba(255,255,255,.06); border-radius:var(--radius); overflow:hidden; }
    .team-photo { height:220px; background-size:cover; background-position:center; filter:brightness(.85); }
    .team-card div:last-child { padding:18px; }
    .about-cta { padding:90px 60px; background:var(--bg2); text-align:center; }
    @media(max-width:900px){ .floating-stat{position:static;margin-top:30px}.about-hero{display:block}.stats-band,.mission-grid,.team-grid{grid-template-columns:1fr 1fr}.values-section{grid-template-columns:1fr} }
    @media(max-width:640px){ .stats-band,.mission-grid,.team-grid{grid-template-columns:1fr}.about-hero,.mission-grid,.values-section,.team-grid,.about-cta{padding-left:24px;padding-right:24px} }
  </style>
</head>
<body>
<nav class="navbar">
  <a href="/sport_plus/" class="logo">SPORT<span>+</span></a>
  <ul class="nav-links">
    <li><a href="/sport_plus/">Home</a></li>
    <li><a href="/sport_plus/terrains">Terrains</a></li>
    <li><a href="/sport_plus/about" class="active">About Us</a></li>
  </ul>
  <div class="nav-actions"><a href="/sport_plus/login" class="btn-ghost">Login</a><a href="/sport_plus/terrains" class="btn-green">Book Now</a></div>
  <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('open')">&#9776;</button>
</nav>
<section class="about-hero">
  <div><p class="label-tag">ABOUT SPORT+</p><h1>BUILT FOR<br/><span>PLAYERS.</span></h1><p class="hero-sub">We make booking sports terrains fast, clear, and reliable across Morocco.</p></div>
  <div class="floating-stat"><strong>3</strong><span>Cities covered</span></div>
</section>
<section class="stats-band">
  <div><strong>534</strong><span>Players</span></div><div><strong>18</strong><span>Terrains</span></div><div><strong>142</strong><span>Bookings</span></div><div><strong>3</strong><span>Cities</span></div>
</section>
<section class="mission-grid">
  <div class="mission-card"><span>01</span><h3>Easy Access</h3><p>Find available terrains by sport, city, time, and price without calling around.</p></div>
  <div class="mission-card"><span>02</span><h3>Trusted Venues</h3><p>We highlight clean, practical, well-located sports spaces for real players.</p></div>
  <div class="mission-card"><span>03</span><h3>Community Matches</h3><p>Join open matches and meet players who want the same kind of game.</p></div>
  <div class="mission-card"><span>04</span><h3>City Coverage</h3><p>Start in Tangier, Marrakesh, and Casablanca with room to expand.</p></div>
  <div class="mission-card"><span>05</span><h3>Clear Pricing</h3><p>See the hourly price before booking and compare options quickly.</p></div>
  <div class="mission-card"><span>06</span><h3>Better Planning</h3><p>Users and admins both get organized dashboards for bookings and activity.</p></div>
</section>
<section class="values-section">
  <div><p class="label-tag">VALUES</p><h2 class="section-title">What guides us</h2></div>
  <div class="value-list">
    <div class="value-item"><strong>01</strong><div><h3>Make sport easier to start</h3><p class="about-desc">The best booking flow is the one that gets players onto the field faster.</p></div></div>
    <div class="value-item"><strong>02</strong><div><h3>Respect local cities</h3><p class="about-desc">Each city has its own sports rhythm, venues, and communities.</p></div></div>
    <div class="value-item"><strong>03</strong><div><h3>Keep the experience honest</h3><p class="about-desc">Clear availability, clear prices, and useful admin tools matter.</p></div></div>
  </div>
</section>
<section class="team-grid">
  <div class="team-card"><div class="team-photo" style="background-image:url('https://i.pinimg.com/1200x/f2/2f/ea/f22fea3216174bbfa3ae9ec9eaff04a8.jpg')"></div><div><h3>Omar</h3><p class="terrain-loc">Operations</p></div></div>
  <div class="team-card"><div class="team-photo" style="background-image:url('https://i.pinimg.com/736x/0c/22/90/0c2290cf168019e6cbfe5cb31187a471.jpg')"></div><div><h3>Sara</h3><p class="terrain-loc">Community</p></div></div>
  <div class="team-card"><div class="team-photo" style="background-image:url('https://i.pinimg.com/1200x/bd/d3/bc/bdd3bc41294ea5a7d04f70c36893d3eb.jpg')"></div><div><h3>Youssef</h3><p class="terrain-loc">Partnerships</p></div></div>
  <div class="team-card"><div class="team-photo" style="background-image:url('https://i.pinimg.com/736x/7a/1e/4c/7a1e4c890c618df4132d895c1fc45f2a.jpg')"></div><div><h3>Nadia</h3><p class="terrain-loc">Product</p></div></div>
</section>
<section class="about-cta"><h2 class="section-title center">Ready to play?</h2><a href="/sport_plus/terrains" class="btn-green large">Book Now →</a></section>
</body>
</html>
