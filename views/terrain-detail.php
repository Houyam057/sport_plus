<?php
// Provided by TerrainController::show(): $terrain, $slots, $avis, $avgNote, $nbAvis
$sportEmoji = ['Football'=>'⚽','Tennis'=>'🎾','Padel'=>'🏓','Basketball'=>'🏀'];
$sportImg   = [
    'Football'   => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=900&q=85',
    'Tennis'     => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=900&q=85',
    'Padel'      => 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=900&q=85',
    'Basketball' => 'https://images.unsplash.com/photo-1546519638405-a9d1b2f5b8b3?w=900&q=85',
];
$cityClass = ['Marrakesh'=>'marrakesh','Casablanca'=>'casa','Tanger'=>'','Tangier'=>''];

$emoji     = $sportEmoji[$terrain['type_sport']] ?? '🏟️';
$imgUrl    = $sportImg[$terrain['type_sport']] ?? $sportImg['Football'];
$badgeCls  = $cityClass[$terrain['localisation']] ?? '';
$theme     = getTheme();

$avisSuccess = flash('avis_success');
$avisError   = flash('avis_error');

function starsHtml($note) {
    $note = (int)round($note);
    $out  = '';
    for ($i = 1; $i <= 5; $i++) {
        $out .= $i <= $note ? '★' : '☆';
    }
    return '<span class="stars-display">' . $out . '</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sport+ | <?= e($terrain['nom']) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/sport_plus/public/css/style.css"/>
  <style>
    .detail-layout { display:grid; grid-template-columns:1fr 380px; gap:48px; padding:60px; background:var(--bg); }
    .terrain-gallery { display:grid; grid-template-columns:1fr 1fr; grid-template-rows:360px 180px; gap:12px; border-radius:var(--radius-lg); overflow:hidden; margin-bottom:40px; }
    .gal-main { grid-column:1/3; background:url('<?= $imgUrl ?>') center/cover; }
    .gal-sm   { background-size:cover; background-position:center; }
    .terrain-info h1 { font-size:36px; font-weight:800; margin-bottom:10px; }
    .terrain-meta { display:flex; gap:24px; align-items:center; flex-wrap:wrap; margin-bottom:28px; }
    .meta-item { display:flex; align-items:center; gap:6px; font-size:14px; color:var(--gray); }
    .section-h { font-size:18px; font-weight:700; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid rgba(255,255,255,0.06); }
    body.light-mode .section-h { border-bottom-color:rgba(0,0,0,.09); }
    .amenities { display:flex; flex-wrap:wrap; gap:12px; margin-bottom:36px; }
    .amenity { background:var(--card); border:1px solid rgba(255,255,255,0.06); padding:10px 16px; border-radius:10px; font-size:13px; }
    body.light-mode .amenity { border-color:rgba(0,0,0,.09); box-shadow:0 1px 3px rgba(0,0,0,.05); }
    .booking-widget { background:var(--card); border:1px solid rgba(255,255,255,0.07); border-radius:var(--radius-lg); padding:28px; position:sticky; top:100px; }
    body.light-mode .booking-widget { border-color:rgba(0,0,0,.1); box-shadow:0 2px 12px rgba(0,0,0,.07); }
    .booking-widget .price-big { font-family:var(--font-display); font-size:48px; color:var(--green); line-height:1; }
    .bk-section { margin-bottom:20px; }
    .bk-section label { display:block; font-size:12px; letter-spacing:1px; text-transform:uppercase; color:var(--gray); font-weight:600; margin-bottom:8px; }
    .bk-section input[type="date"], .bk-section select { width:100%; background:var(--bg3); border:1px solid var(--gray2); color:var(--white); padding:12px 16px; border-radius:var(--radius); font-size:15px; font-family:var(--font-body); outline:none; }
    .bk-section input[type="date"]:focus, .bk-section select:focus { border-color:var(--green); }
    .time-slots { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
    .slot { padding:10px; text-align:center; border-radius:8px; border:1px solid var(--gray2); font-size:13px; cursor:pointer; transition:.2s; background:transparent; color:var(--white); font-family:var(--font-body); }
    .slot:hover, .slot.selected { border-color:var(--green); color:var(--green); }
    .slot.selected { background:var(--green); color:#000; font-weight:700; }
    .slot.taken { opacity:.3; cursor:not-allowed; text-decoration:line-through; pointer-events:none; }
    .total-row { display:flex; justify-content:space-between; padding:12px 0; border-top:1px solid rgba(255,255,255,0.06); font-size:14px; }
    .total-row.big { font-size:18px; font-weight:700; border-color:rgba(255,255,255,0.1); }
    body.light-mode .total-row { border-top-color:rgba(0,0,0,.08); }
    .reviews-section { padding:0 60px 70px; background:var(--bg); }
    .review-form-card { background:var(--card); border:1px solid rgba(255,255,255,.06); border-radius:var(--radius); padding:24px; margin-bottom:28px; }
    body.light-mode .review-form-card { border-color:rgba(0,0,0,.09); }
    @media(max-width:1100px){ .detail-layout{grid-template-columns:1fr;padding:30px 24px} .booking-widget{position:relative;top:auto} .reviews-section{padding:0 24px 50px} }
  </style>
</head>
<body class="<?= $theme === 'light' ? 'light-mode' : '' ?>">

<nav class="navbar">
  <a href="/sport_plus/" class="logo">SPORT<span>+</span></a>
  <ul class="nav-links">
    <li><a href="/sport_plus/">Home</a></li>
    <li><a href="/sport_plus/terrains" class="active">Terrains</a></li>
    <li><a href="/sport_plus/#about-us">About Us</a></li>
  </ul>
  <div class="nav-actions">
    <?php if (isLoggedIn()): ?>
      <a href="/sport_plus/dashboard" class="btn-ghost"><?= t('Dashboard','Tableau de bord') ?></a>
      <a href="/sport_plus/logout" class="btn-green"><?= t('Logout','Déconnexion') ?></a>
    <?php else: ?>
      <a href="/sport_plus/login" class="btn-ghost"><?= t('Login','Connexion') ?></a>
      <a href="/sport_plus/terrains" class="btn-green"><?= t('Book Now','Réserver') ?></a>
    <?php endif; ?>
  </div>
  <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('open')">&#9776;</button>
</nav>

<div style="height:80px"></div>
<div style="padding:20px 60px;background:var(--bg2);border-bottom:1px solid rgba(255,255,255,0.05)">
  <a href="/sport_plus/terrains" style="color:var(--gray);font-size:14px">← <?= t('Back to Terrains','Retour aux terrains') ?></a>
</div>

<?php if ($avisSuccess || $avisError): ?>
<div style="padding:14px 60px;<?= $avisSuccess ? 'background:rgba(76,255,114,.1);color:#4cff72;border-bottom:1px solid rgba(76,255,114,.2)' : 'background:rgba(255,76,76,.1);color:#ff7c7c;border-bottom:1px solid rgba(255,76,76,.2)' ?>;font-size:14px">
  <?= e($avisSuccess ?: $avisError) ?>
</div>
<?php endif; ?>

<div class="detail-layout">
  <!-- LEFT: Info -->
  <div>
    <div class="terrain-gallery">
      <div class="gal-main"></div>
      <div class="gal-sm" style="background-image:url('https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=400&q=80')"></div>
      <div class="gal-sm" style="background-image:url('https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&q=80')"></div>
    </div>

    <div class="terrain-info">
      <div class="terrain-tag-row" style="margin-bottom:12px">
        <span class="sport-tag"><?= e($terrain['type_sport']) ?></span>
        <span class="city-badge <?= $badgeCls ?>"><?= e($terrain['localisation']) ?></span>
      </div>
      <h1><?= e($terrain['nom']) ?></h1>
      <div class="terrain-meta">
        <span class="meta-item">📍 <?= e($terrain['localisation']) ?></span>
        <span class="meta-item"><?= $emoji ?> <?= e($terrain['type_sport']) ?></span>
        <?php if ($avgNote > 0): ?>
          <span class="meta-item"><?= starsHtml($avgNote) ?> &nbsp;<?= $avgNote ?> (<?= $nbAvis ?> <?= t('reviews','avis') ?>)</span>
        <?php endif; ?>
        <span class="meta-item">💰 <strong style="color:var(--green)"><?= fmtPrice($terrain['prix']) ?>/h</strong></span>
      </div>

      <h3 class="section-h"><?= t('About this terrain','À propos du terrain') ?></h3>
      <p style="color:var(--gray);line-height:1.8;font-size:15px;margin-bottom:36px">
        <?= t(
          "A quality {$terrain['type_sport']} terrain located in {$terrain['localisation']}. Book your slot and enjoy a great game with friends or colleagues.",
          "Un terrain de {$terrain['type_sport']} de qualité situé à {$terrain['localisation']}. Réservez votre créneau et profitez d'une bonne partie entre amis."
        ) ?>
      </p>

      <h3 class="section-h"><?= t('Services & Amenities','Services & Équipements') ?></h3>
      <div class="amenities">
        <div class="amenity">🅿️ <?= t('Parking','Parking') ?></div>
        <div class="amenity">💡 <?= t('Floodlights','Éclairage') ?></div>
        <div class="amenity">🚿 <?= t('Showers','Douches') ?></div>
        <div class="amenity">👕 <?= t('Changing rooms','Vestiaires') ?></div>
        <div class="amenity">☕ <?= t('Refreshments','Boissons') ?></div>
        <div class="amenity">📶 WiFi</div>
      </div>

      <!-- REVIEWS SECTION -->
      <h3 class="section-h" id="reviews-anchor">
        <?= t('Reviews','Avis') ?>
        <?php if ($avgNote > 0): ?>
          <span style="font-size:14px;font-weight:400;color:var(--gray);margin-left:10px"><?= starsHtml($avgNote) ?> <?= $avgNote ?>/5</span>
        <?php endif; ?>
      </h3>

      <?php if (isLoggedIn()): ?>
      <div class="review-form-card">
        <div style="font-weight:700;margin-bottom:16px"><?= t('Write a review','Écrire un avis') ?></div>
        <form method="post" action="/sport_plus/avis/store">
          <input type="hidden" name="terrain_id" value="<?= (int)$terrain['id'] ?>"/>
          <div style="margin-bottom:14px">
            <div style="font-size:12px;color:var(--gray);text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:8px"><?= t('Rating','Note') ?></div>
            <div id="starPicker" style="display:flex;gap:4px">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <button type="button" class="star-btn" data-val="<?= $i ?>" onclick="pickStar(<?= $i ?>)">★</button>
              <?php endfor; ?>
            </div>
            <input type="hidden" name="note" id="noteInput" value="5"/>
          </div>
          <div class="form-group" style="margin-bottom:14px">
            <label><?= t('Comment','Commentaire') ?></label>
            <textarea name="commentaire" rows="3" placeholder="<?= t('Share your experience...','Partagez votre expérience...') ?>" style="resize:vertical" required></textarea>
          </div>
          <button type="submit" class="btn-green small"><?= t('Post Review','Publier') ?></button>
        </form>
      </div>
      <?php else: ?>
      <p style="color:var(--gray);font-size:14px;margin-bottom:24px">
        <a href="/sport_plus/login" style="color:var(--green)"><?= t('Log in','Connectez-vous') ?></a>
        <?= t(' to leave a review.',' pour laisser un avis.') ?>
      </p>
      <?php endif; ?>

      <?php if (empty($avis)): ?>
        <p style="color:var(--gray);font-size:14px"><?= t('No reviews yet. Be the first!','Aucun avis pour l\'instant. Soyez le premier !') ?></p>
      <?php else: ?>
        <div class="reviews-list">
          <?php foreach ($avis as $av): ?>
            <div class="review-item">
              <div class="review-header">
                <span class="reviewer"><?= e($av['user_nom']) ?></span>
                <?= starsHtml($av['note']) ?>
              </div>
              <p class="review-text"><?= e($av['commentaire']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- RIGHT: Booking Widget -->
  <aside>
    <div class="booking-widget">
      <div style="margin-bottom:20px">
        <div class="price-big"><?= fmtPrice($terrain['prix']) ?> <small style="font-size:16px;font-family:var(--font-body);">/h</small></div>
        <div style="font-size:13px;color:var(--gray);margin-top:6px"><?= e($terrain['type_sport']) ?> · <?= e($terrain['localisation']) ?></div>
      </div>

      <?php if (isLoggedIn()): ?>
      <form action="/sport_plus/reservation/create" method="post" id="bookingForm">
        <input type="hidden" name="terrain_id" value="<?= (int)$terrain['id'] ?>"/>
        <input type="hidden" name="heure_debut" id="selectedSlot" value=""/>

        <div class="bk-section">
          <label><?= t('Select Date','Choisir la date') ?></label>
          <input type="date" name="date" id="dateInput" required/>
        </div>

        <div class="bk-section">
          <label><?= t('Available Slots','Créneaux disponibles') ?></label>
          <div class="time-slots">
            <?php foreach ($slots as $slot): ?>
              <button type="button"
                class="slot <?= $slot['available'] ? '' : 'taken' ?>"
                data-time="<?= e($slot['time']) ?>"
                <?= $slot['available'] ? 'onclick="selectSlot(this)"' : '' ?>>
                <?= e($slot['time']) ?>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="bk-section">
          <label><?= t('Duration','Durée') ?></label>
          <select name="duration" id="durationSelect" onchange="updateTotal(this.value)">
            <option value="1">1 <?= t('hour','heure') ?></option>
            <option value="2">2 <?= t('hours','heures') ?></option>
            <option value="3">3 <?= t('hours','heures') ?></option>
          </select>
        </div>

        <div>
          <div class="total-row">
            <span><?= fmtPrice($terrain['prix']) ?> × <span id="hrs">1</span>h</span>
            <span id="subtotal"><?= fmtPrice($terrain['prix']) ?></span>
          </div>
          <div class="total-row big">
            <span><?= t('Total','Total') ?></span>
            <span style="color:var(--green)" id="total"><?= fmtPrice($terrain['prix']) ?></span>
          </div>
        </div>

        <button type="submit" class="btn-green" id="confirmBtn"
          style="width:100%;justify-content:center;margin-top:20px;display:flex;opacity:.45;cursor:not-allowed"
          disabled>
          <?= t('Select a time slot first','Sélectionnez un créneau') ?>
        </button>
      </form>

      <?php else: ?>
      <div style="text-align:center;padding:16px 0">
        <p style="color:var(--gray);margin-bottom:16px;font-size:14px"><?= t('Log in to book this terrain.','Connectez-vous pour réserver.') ?></p>
        <a href="/sport_plus/login" class="btn-green" style="display:flex;justify-content:center;width:100%">
          <?= t('Login to Book','Se connecter') ?>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </aside>
</div>

<footer class="footer">
  <div class="footer-top">
    <div class="footer-brand"><span class="logo">SPORT<span>+</span></span><p>Making sports accessible for everyone.</p></div>
    <div class="footer-col"><h4>Explore</h4><a href="/sport_plus/terrains">Terrains</a><a href="/sport_plus/about">About Us</a></div>
    <div class="footer-col"><h4>Support</h4><a href="#">FAQ</a><a href="#">Terms</a></div>
  </div>
  <div class="footer-bottom"><p>© 2026 Sport+. All rights reserved.</p></div>
</footer>

<script>
  const price = <?= (int)$terrain['prix'] ?>;
  const cur   = '<?= getCurrency() ?>';

  function fmtPrice(mad) {
    if (cur === 'EUR') return Math.round(mad / 10) + ' €';
    return mad + ' MAD';
  }

  function selectSlot(btn) {
    document.querySelectorAll('.slot').forEach(s => s.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('selectedSlot').value = btn.dataset.time;
    const cb = document.getElementById('confirmBtn');
    cb.disabled = false;
    cb.style.opacity = '1';
    cb.style.cursor  = 'pointer';
    cb.textContent = '<?= t('Confirm Booking →','Confirmer la réservation →') ?>';
  }

  function updateTotal(hours) {
    const h = parseInt(hours);
    document.getElementById('hrs').textContent    = h;
    document.getElementById('subtotal').textContent = fmtPrice(price * h);
    document.getElementById('total').textContent    = fmtPrice(price * h);
  }

  function pickStar(val) {
    document.getElementById('noteInput').value = val;
    document.querySelectorAll('.star-btn').forEach((b, i) => {
      b.classList.toggle('active', i < val);
    });
  }
  // Init stars
  pickStar(5);

  const di = document.getElementById('dateInput');
  if (di) {
    di.min   = new Date().toISOString().split('T')[0];
    di.value = new Date().toISOString().split('T')[0];
  }
</script>
</body>
</html>
