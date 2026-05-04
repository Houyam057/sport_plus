<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sport+ | Matches</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/sport_plus/public/css/style.css"/>
  <style>
    .matches-layout { padding: 60px; background: var(--bg); min-height: 80vh; }
    .matches-top { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; gap: 18px; }
    .matches-top h1 { font-size: 36px; font-weight: 800; }
    .matches-top p { color: var(--gray); font-size: 15px; margin-top: 4px; }
    .matches-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 24px; }
    .match-card-full { background: var(--card); border: 1px solid rgba(255,255,255,0.06); border-radius: var(--radius-lg); overflow: hidden; transition: transform .3s, border-color .3s; }
    .match-card-full:hover { transform: translateY(-4px); border-color: rgba(76,255,114,0.25); }
    .match-card-header { padding: 20px 20px 14px; background: var(--bg3); border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
    .match-sport-badge { display: flex; align-items: center; gap: 8px; }
    .match-sport-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(76,255,114,0.12); display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .match-title { font-size: 16px; font-weight: 700; }
    .match-type { font-size: 12px; color: var(--gray); margin-top: 2px; }
    .match-body { padding: 18px 20px; }
    .match-detail-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; font-size: 13px; color: var(--gray); }
    .match-detail-row strong { color: var(--white); }
    .players-bar { margin: 16px 0; }
    .players-bar-label { display: flex; justify-content: space-between; font-size: 12px; color: var(--gray); margin-bottom: 8px; }
    .players-bar-label span:last-child { color: var(--green); font-weight: 700; }
    .progress-track { height: 6px; background: var(--bg3); border-radius: 3px; overflow: hidden; }
    .progress-fill { height: 100%; background: var(--green); border-radius: 3px; }
    .match-footer { padding: 14px 20px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; }
    .match-creator { font-size: 12px; color: var(--gray); }
    .match-creator strong { color: var(--white); }
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 200; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal { background: var(--bg2); border: 1px solid rgba(255,255,255,0.08); border-radius: var(--radius-lg); padding: 36px; width: 100%; max-width: 500px; position: relative; max-height: 90vh; overflow-y: auto; }
    .modal-close { position: absolute; top: 20px; right: 20px; background: none; border: none; color: var(--gray); font-size: 22px; cursor: pointer; }
    @media (max-width: 768px) { .matches-layout { padding: 30px 16px; } .matches-top { flex-direction: column; align-items: flex-start; } }
  </style>
</head>
<body>
<nav class="navbar">
  <a href="/sport_plus/" class="logo">SPORT<span>+</span></a>
  <ul class="nav-links"><li><a href="/sport_plus/">Home</a></li><li><a href="/sport_plus/terrains">Terrains</a></li><li><a href="/sport_plus/#about-us">About Us</a></li></ul>
  <div class="nav-actions"><a href="/sport_plus/dashboard" class="btn-ghost">Dashboard</a><a href="/sport_plus/terrains" class="btn-green">Book Now</a></div>
  <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('open')">&#9776;</button>
</nav>
<div class="page-header"><span class="label-tag">COMMUNITY</span><h1>Find a <span style="color:var(--green)">Match</span></h1><p>Join open games across Tangier, Marrakesh, and Casablanca</p></div>
<div class="matches-layout">
  <div class="matches-top"><div><h1>Open Matches</h1><p id="matchCount">6 matches looking for players</p></div><button class="btn-green" onclick="document.getElementById('createModal').classList.add('open')">+ Create Match</button></div>
  <div class="sport-pills" style="justify-content:flex-start;margin-bottom:32px">
    <button class="pill active" data-city="all">All Cities</button><button class="pill" data-city="tangier">Tangier</button><button class="pill" data-city="marrakesh">Marrakesh</button><button class="pill" data-city="casablanca">Casablanca</button>
  </div>
  <div class="matches-grid">
    <?php
      $matches = [
        ['city'=>'Tangier','badge'=>'','icon'=>'⚽','title'=>'5v5 Football','terrain'=>'Stade Al Majd','area'=>'Zone Industrielle','date'=>'Tomorrow','time'=>'18:00 - 19:00','players'=>'4 / 10 remaining','width'=>'40%','creator'=>'Youssef A.'],
        ['city'=>'Tangier','badge'=>'','icon'=>'🎾','title'=>'Tennis Singles','terrain'=>'Royal Tennis Club','area'=>'Malabata','date'=>'Sunday','time'=>'09:00 - 10:00','players'=>'1 / 2 remaining','width'=>'50%','creator'=>'Hamid K.'],
        ['city'=>'Marrakesh','badge'=>'marrakesh','icon'=>'🏓','title'=>'Padel Doubles','terrain'=>'Palmeraie Padel','area'=>'Hivernage','date'=>'Saturday','time'=>'10:00 - 11:00','players'=>'2 / 4 remaining','width'=>'50%','creator'=>'Sara M.'],
        ['city'=>'Marrakesh','badge'=>'marrakesh','icon'=>'⚽','title'=>'7v7 Football','terrain'=>'Atlas Football Park','area'=>'Gueliz','date'=>'Tonight','time'=>'21:00 - 22:00','players'=>'8 / 14 remaining','width'=>'57%','creator'=>'Omar D.'],
        ['city'=>'Casablanca','badge'=>'casa','icon'=>'🏀','title'=>'3v3 Basketball','terrain'=>'Court Central Casa','area'=>'Ain Diab','date'=>'Friday','time'=>'20:00 - 21:00','players'=>'4 / 6 remaining','width'=>'66%','creator'=>'Karim B.'],
        ['city'=>'Casablanca','badge'=>'casa','icon'=>'🏓','title'=>'Padel After Work','terrain'=>'Casa Padel Arena','area'=>'Maarif','date'=>'Thursday','time'=>'19:00 - 20:00','players'=>'3 / 4 remaining','width'=>'75%','creator'=>'Nadia R.'],
      ];
      foreach ($matches as $m):
    ?>
    <div class="match-card-full match-filter-card" data-city="<?= strtolower($m['city']) ?>">
      <div class="match-card-header">
        <div class="match-sport-badge"><div class="match-sport-icon"><?= $m['icon'] ?></div><div><div class="match-title"><?= $m['title'] ?></div><div class="match-type">Public · Casual</div></div></div>
        <span class="city-badge <?= $m['badge'] ?>"><?= $m['city'] ?></span>
      </div>
      <div class="match-body">
        <div class="match-detail-row">📅 <strong><?= $m['date'] ?></strong> · <?= $m['time'] ?></div>
        <div class="match-detail-row">📍 <strong><?= $m['terrain'] ?></strong> · <?= $m['area'] ?></div>
        <div class="match-detail-row">💰 <strong>Split cost</strong> · <?= $m['city'] ?></div>
        <div class="players-bar"><div class="players-bar-label"><span>Players</span><span><?= $m['players'] ?></span></div><div class="progress-track"><div class="progress-fill" style="width:<?= $m['width'] ?>"></div></div></div>
      </div>
      <div class="match-footer"><div class="match-creator">By <strong><?= $m['creator'] ?></strong></div><button class="btn-green small">Join Match</button></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<div class="modal-overlay" id="createModal">
  <div class="modal"><button class="modal-close" onclick="document.getElementById('createModal').classList.remove('open')">×</button><h2>Create a Match</h2><p>Set up your game and let others join</p>
    <div class="form-group"><label>Sport</label><select><option>Football</option><option>Tennis</option><option>Padel</option><option>Basketball</option></select></div>
    <div class="form-group"><label>Terrain</label><select><optgroup label="Tangier"><option>Stade Al Majd</option><option>Royal Tennis Club</option></optgroup><optgroup label="Marrakesh"><option>Palmeraie Padel</option><option>Atlas Football Park</option></optgroup><optgroup label="Casablanca"><option>Casa Padel Arena</option><option>Court Central Casa</option></optgroup></select></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px"><div class="form-group"><label>Date</label><input type="date"/></div><div class="form-group"><label>Time</label><input type="time" value="18:00"/></div></div>
    <button class="btn-green" style="width:100%;justify-content:center" onclick="alert('Match created!');document.getElementById('createModal').classList.remove('open')">Create Match →</button>
  </div>
</div>
<footer class="footer"><div class="footer-top"><div class="footer-brand"><span class="logo">SPORT<span>+</span></span><p>Making sports accessible for everyone, everywhere.</p></div><div class="footer-col"><h4>Explore</h4><a href="/sport_plus/terrains">Terrains</a><a href="/sport_plus/matches">Matches</a></div><div class="footer-col"><h4>Support</h4><a href="#">FAQ</a><a href="#">Terms & Conditions</a></div><div class="footer-col newsletter"><h4>Newsletter</h4><p>Get updates about new terrains and exclusive offers.</p><div class="newsletter-form"><input type="email" placeholder="Your email"/><button class="btn-green small">→</button></div></div></div><div class="footer-bottom"><p>© 2026 Sport+. All rights reserved.</p></div></footer>
<script>
  document.querySelectorAll('[data-city]').forEach(btn => {
    btn.addEventListener('click', () => {
      const city = btn.dataset.city;
      btn.closest('.sport-pills').querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      let count = 0;
      document.querySelectorAll('.match-filter-card').forEach(card => {
        const visible = city === 'all' || card.dataset.city === city;
        card.style.display = visible ? '' : 'none';
        if (visible) count++;
      });
      document.getElementById('matchCount').textContent = count + ' matches looking for players';
    });
  });
  document.getElementById('createModal').addEventListener('click', function(e) { if (e.target === this) this.classList.remove('open'); });
</script>
</body>
</html>
