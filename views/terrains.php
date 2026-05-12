<?php
// $terrains is injected by TerrainController::index() from the database
// Fallback: empty array if controller didn't provide it
if (!isset($terrains)) $terrains = [];
require_once __DIR__ . '/../config/sport_images.php';
$_cityBadge = ['marrakesh'=>'marrakesh','casablanca'=>'casa'];
$_sportTag  = ['tennis'=>'tennis','padel'=>'padel','basketball'=>'basketball'];
// Normalise city name to a filter key
function _cityKey(string $loc): string {
    $l = strtolower(trim($loc));
    if (str_contains($l,'tanger') || str_contains($l,'tangier')) return 'tangier';
    if (str_contains($l,'marrakesh') || str_contains($l,'marrakech')) return 'marrakesh';
    if (str_contains($l,'casablanca')) return 'casablanca';
    return $l;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sport+ | Terrains</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/sport_plus/public/css/style.css"/>
  <style>
    .filter-hidden { display: none !important; }
    .terrains-layout { display: grid; grid-template-columns: 280px 1fr; gap: 40px; padding: 60px; background: var(--bg); min-height: 80vh; }
    .sidebar { position: sticky; top: 100px; align-self: start; }
    .sidebar h3 { font-size: 13px; font-weight: 700; letter-spacing: 2px; color: var(--gray); text-transform: uppercase; margin-bottom: 16px; }
    .filter-group { margin-bottom: 32px; }
    .filter-btn { display: block; width: 100%; text-align: left; background: var(--card); border: 1px solid rgba(255,255,255,0.06); color: var(--white); padding: 10px 16px; border-radius: var(--radius); font-size: 14px; font-family: var(--font-body); cursor: pointer; margin-bottom: 8px; transition: all .2s; }
    .filter-btn:hover, .filter-btn.active { border-color: var(--green); color: var(--green); background: rgba(76,255,114,0.06); }
    .range-slider { width: 100%; accent-color: var(--green); }
    .price-display { font-size: 13px; color: var(--gray); margin-top: 8px; }
    .results-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; gap: 16px; }
    .results-header h2 { font-size: 22px; font-weight: 800; }
    .results-header span { color: var(--gray); font-size: 14px; }
    .sort-select { background: var(--card); border: 1px solid var(--gray2); color: var(--white); padding: 8px 14px; border-radius: 8px; font-family: var(--font-body); font-size: 14px; outline: none; cursor: pointer; }
    .terrains-grid-full { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }
    .terrain-card-full .terrain-img { height: 220px; position: relative; }
    .terrain-card-full .terrain-img .availability { position: absolute; top: 12px; right: 12px; background: rgba(13,13,13,0.85); border: 1px solid var(--green); color: var(--green); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
    .terrain-rating { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--gray); margin-bottom: 8px; }
    .stars { color: #ffc84c; letter-spacing: 1px; }
    @media (max-width: 900px) { .terrains-layout { grid-template-columns: 1fr; padding: 30px 24px; } .sidebar { position: relative; top: auto; } .results-header { align-items: flex-start; flex-direction: column; } }
  </style>
</head>
<body>
<nav class="navbar">
  <a href="/sport_plus/" class="logo">SPORT<span>+</span></a>
  <ul class="nav-links">
    <li><a href="/sport_plus/">Home</a></li>
    <li><a href="/sport_plus/terrains" class="active">Terrains</a></li>
    <li><a href="/sport_plus/#about-us">About Us</a></li>
  </ul>
  <div class="nav-actions"><a href="/sport_plus/login" class="btn-ghost">Login</a><a href="/sport_plus/terrains" class="btn-green">Book Now</a></div>
  <button class="hamburger" onclick="toggleMenu()">&#9776;</button>
</nav>

<div class="page-header">
  <span class="label-tag">EXPLORE</span>
  <h1>Find Your <span style="color:var(--green)">Terrain</span></h1>
  <p>Browse and book the best sports fields in Tangier, Marrakesh, and Casablanca</p>
</div>

<div class="terrains-layout">
  <aside class="sidebar">
    <div class="filter-group">
      <h3>Sport Type</h3>
      <button class="filter-btn active" data-filter-group="sport" data-filter-value="all" onclick="setFilter(this)">All Sports</button>
      <button class="filter-btn" data-filter-group="sport" data-filter-value="football" onclick="setFilter(this)">Football</button>
      <button class="filter-btn" data-filter-group="sport" data-filter-value="tennis" onclick="setFilter(this)">Tennis</button>
      <button class="filter-btn" data-filter-group="sport" data-filter-value="padel" onclick="setFilter(this)">Padel</button>
      <button class="filter-btn" data-filter-group="sport" data-filter-value="basketball" onclick="setFilter(this)">Basketball</button>
    </div>
    <div class="filter-group">
      <h3>City</h3>
      <button class="filter-btn active" data-filter-group="city" data-filter-value="all" onclick="setFilter(this)">All Cities</button>
      <button class="filter-btn" data-filter-group="city" data-filter-value="tangier" onclick="setFilter(this)">Tangier</button>
      <button class="filter-btn" data-filter-group="city" data-filter-value="marrakesh" onclick="setFilter(this)">Marrakesh</button>
      <button class="filter-btn" data-filter-group="city" data-filter-value="casablanca" onclick="setFilter(this)">Casablanca</button>
    </div>
    <div class="filter-group">
      <h3>Price (MAD/h)</h3>
      <input type="range" class="range-slider" min="50" max="300" value="200" oninput="updatePrice(this.value)" id="priceRange"/>
      <p class="price-display">Up to <strong id="priceVal" style="color:var(--green)">200</strong> MAD/h</p>
    </div>
    <div class="filter-group">
      <h3>Time Slot</h3>
      <button class="filter-btn active" onclick="this.closest('.filter-group').querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));this.classList.add('active')">Any Time</button>
      <button class="filter-btn" onclick="this.closest('.filter-group').querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));this.classList.add('active')">Morning (6-12h)</button>
      <button class="filter-btn" onclick="this.closest('.filter-group').querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));this.classList.add('active')">Afternoon (12-18h)</button>
      <button class="filter-btn" onclick="this.closest('.filter-group').querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));this.classList.add('active')">Evening (18-24h)</button>
    </div>
  </aside>

  <div class="terrain-results">
    <div class="results-header">
      <div><h2>Available Terrains</h2><span id="terrainCount"><?= count($terrains) ?> terrain<?= count($terrains) !== 1 ? 's' : '' ?> found</span></div>
      <select class="sort-select"><option>Sort by: Relevance</option><option>Price: Low to High</option><option>Price: High to Low</option><option>Top Rated</option></select>
    </div>
    <div class="terrains-grid-full">
      <?php foreach ($terrains as $t):
        $sport   = $t['type_sport'] ?? 'Football';
        $city    = $t['localisation'] ?? '';
        $cityKey = _cityKey($city);
        $sKey    = strtolower($sport);
        $img     = sportImage($sport);
        $badge   = $_cityBadge[$cityKey] ?? '';
        $tag     = $_sportTag[$sKey]     ?? '';
      ?>
      <div class="terrain-card terrain-card-full filter-card" data-city="<?= $cityKey ?>" data-sport="<?= $sKey ?>">
        <div class="terrain-img" style="background:url('<?= $img ?>') center/cover"><span class="availability">Available</span></div>
        <div class="terrain-body">
          <div class="terrain-tag-row"><span class="sport-tag <?= $tag ?>"><?= e($sport) ?></span><span class="city-badge <?= $badge ?>"><?= e($city) ?></span></div>
          <div class="terrain-rating"><span class="stars">★★★★★</span></div>
          <h3><?= e($t['nom']) ?></h3>
          <p class="terrain-loc"><?= e($city) ?></p>
          <div class="terrain-footer"><span class="price"><?= e($t['prix']) ?> MAD<small>/h</small></span><a href="/sport_plus/terrain/<?= (int)$t['id'] ?>" class="btn-green small">Book</a></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="footer-top">
    <div class="footer-brand"><span class="logo">SPORT<span>+</span></span><p>Making sports accessible for everyone, everywhere.</p></div>
    <div class="footer-col"><h4>Explore</h4><a href="/sport_plus/terrains">Terrains</a><a href="/sport_plus/#about-us">About Us</a></div>
    <div class="footer-col"><h4>Support</h4><a href="#">FAQ</a><a href="#">Terms & Conditions</a><a href="#">Privacy Policy</a></div>
    <div class="footer-col newsletter"><h4>Newsletter</h4><p>Get updates about new terrains and exclusive offers.</p><div class="newsletter-form"><input type="email" placeholder="Your email"/><button class="btn-green small">→</button></div></div>
  </div>
  <div class="footer-bottom"><p>© 2026 Sport+. All rights reserved.</p></div>
</footer>

<script>
  function toggleMenu() { document.querySelector('.nav-links').classList.toggle('open'); }
  const filters = { city: 'all', sport: 'all' };
  function setFilter(btn) {
    const group = btn.dataset.filterGroup;
    filters[group] = btn.dataset.filterValue;
    btn.closest('.filter-group').querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    let count = 0;
    document.querySelectorAll('.filter-card').forEach(card => {
      const cityOk = filters.city === 'all' || card.getAttribute('data-city') === filters.city;
      const sportOk = filters.sport === 'all' || card.getAttribute('data-sport') === filters.sport;
      const visible = cityOk && sportOk;
      if (visible) {
        card.style.removeProperty('display');
        card.classList.remove('filter-hidden');
      } else {
        card.style.setProperty('display', 'none', 'important');
        card.classList.add('filter-hidden');
      }
      if (visible) count++;
    });
    document.getElementById('terrainCount').textContent = count + ' terrains found';
  }
  function updatePrice(val) { document.getElementById('priceVal').textContent = val; }
</script>
</body>
</html>
