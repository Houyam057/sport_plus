<?php
// Variables: $user, $reservations, $stats
$sportEmoji  = ['Football'=>'⚽','Tennis'=>'🎾','Padel'=>'🏓','Basketball'=>'🏀'];
$statusClass = ['pending'=>'badge-yellow','confirmed'=>'badge-green','cancelled'=>'badge-red','completed'=>'badge-gray'];
$statusLabel = ['pending'=>'Pending','confirmed'=>'Confirmed','cancelled'=>'Cancelled','completed'=>'Completed'];
$statusLabelFR = ['pending'=>'En attente','confirmed'=>'Confirmé','cancelled'=>'Annulé','completed'=>'Terminé'];
$cityClass   = ['Marrakesh'=>'marrakesh','Casablanca'=>'casa','Tanger'=>'','Tangier'=>''];

$today      = date('Y-m-d');
$upcoming   = array_filter($reservations, fn($r) => in_array($r['statut'],['confirmed','pending']) && $r['date'] >= $today);
$bookingMsg = flash('booking_success');
$errorMsg   = flash('booking_error');

$userName    = e($_SESSION['user_nom']   ?? $user['nom']   ?? 'User');
$userEmail   = e($_SESSION['user_email'] ?? $user['email'] ?? '');
$userInitial = strtoupper(mb_substr($_SESSION['user_nom'] ?? $user['nom'] ?? 'U', 0, 1));
$theme       = getTheme();

function sl($en, $fr = '') { // shorthand label with lang
    return t($en, $fr);
}
?>
<!DOCTYPE html>
<html lang="<?= getLang() === 'FR' ? 'fr' : 'en' ?>">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sport+ | <?= t('Dashboard','Tableau de bord') ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/sport_plus/public/css/style.css"/>
  <style>
    .tab-content { display:none; }
    .tab-content.visible { display:block; }
    .empty-state { text-align:center; padding:60px 20px; color:var(--gray); }
    .empty-state a { color:var(--green); }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.8); z-index:9999; align-items:center; justify-content:center; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:var(--bg2); border:1px solid rgba(255,255,255,.1); border-radius:var(--radius-lg); padding:36px; max-width:440px; width:92%; text-align:center; }
    body.light-mode .modal-box { border-color:rgba(0,0,0,.1); box-shadow:0 8px 40px rgba(0,0,0,.12); }
    .modal-icon { font-size:52px; margin-bottom:16px; }
    .modal-box h2 { font-size:22px; margin-bottom:8px; }
    .modal-box p { color:var(--gray); font-size:14px; margin-bottom:24px; }
    .modal-box-left { text-align:left; max-width:560px; }
    .profile-form-max { max-width:480px; }
    @media(max-width:900px){ .dash-main{padding:24px 16px} }
  </style>
</head>
<body class="<?= $theme === 'light' ? 'light-mode' : '' ?>">

<nav class="navbar">
  <a href="/sport_plus/" class="logo">SPORT<span>+</span></a>
  <ul class="nav-links">
    <li><a href="/sport_plus/"><?= t('Home','Accueil') ?></a></li>
    <li><a href="/sport_plus/terrains"><?= t('Terrains','Terrains') ?></a></li>
    <li><a href="/sport_plus/#about-us"><?= t('About','À propos') ?></a></li>
  </ul>
  <div class="nav-actions">
    <a href="/sport_plus/logout" class="btn-ghost"><?= t('Logout','Déconnexion') ?></a>
    <a href="/sport_plus/terrains" class="btn-green"><?= t('Book Now','Réserver') ?></a>
  </div>
  <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('open')">&#9776;</button>
</nav>

<div class="dash-layout">
  <aside class="sidebar-nav">
    <div class="user-box">
      <div class="user-avatar"><?= $userInitial ?></div>
      <div class="user-name"><?= $userName ?></div>
      <div class="user-email"><?= $userEmail ?></div>
    </div>
    <p class="nav-section-label"><?= t('Main','Principal') ?></p>
    <div class="nav-item active" onclick="showTab('overview',this)">📊 <?= t('Overview','Aperçu') ?></div>
    <div class="nav-item" onclick="showTab('reservations',this)">📅 <?= t('My Reservations','Mes réservations') ?></div>
    <p class="nav-section-label"><?= t('Account','Compte') ?></p>
    <div class="nav-item" onclick="showTab('profile',this)">👤 <?= t('Profile','Profil') ?></div>
    <div class="nav-item" onclick="showTab('history',this)">🕒 <?= t('History','Historique') ?></div>
    <div class="nav-item" onclick="showTab('settings',this)">⚙️ <?= t('Settings','Paramètres') ?></div>
    <div style="padding:20px 24px;margin-top:auto">
      <a href="/sport_plus/logout" style="color:var(--gray);font-size:13px">← <?= t('Logout','Déconnexion') ?></a>
    </div>
  </aside>

  <main class="dash-main">

    <!-- OVERVIEW TAB -->
    <div class="tab-content visible" id="tab-overview">
      <div class="dash-header">
        <h1><?= t("Welcome back, $userName!", "Bienvenue, $userName !") ?></h1>
        <p><?= t("Here's a summary of your activity.","Voici un résumé de votre activité.") ?></p>
      </div>
      <div class="stats-row">
        <div class="mini-stat"><div class="ms-val"><?= $stats['total'] ?></div><div class="ms-label"><?= t('Total Bookings','Réservations') ?></div></div>
        <div class="mini-stat"><div class="ms-val"><?= $stats['upcoming'] ?></div><div class="ms-label"><?= t('Upcoming','À venir') ?></div></div>
        <div class="mini-stat"><div class="ms-val"><?= $stats['completed'] ?></div><div class="ms-label"><?= t('Completed','Terminées') ?></div></div>
        <div class="mini-stat"><div class="ms-val"><?= count($reservations) > 0 ? fmtPrice(array_sum(array_column($reservations,'prix'))) : fmtPrice(0) ?></div><div class="ms-label"><?= t('Total Spent','Total dépensé') ?></div></div>
      </div>

      <div class="dash-section">
        <div class="dash-section-header">
          <h2><?= t('Upcoming Reservations','Prochaines réservations') ?></h2>
          <a href="#" onclick="showTab('reservations',null)" class="btn-outline" style="padding:8px 16px;font-size:13px"><?= t('View All','Tout voir') ?></a>
        </div>
        <?php if (empty($upcoming)): ?>
          <div class="empty-state">
            <p style="margin-bottom:12px">🗓 <?= t('No upcoming reservations.','Aucune réservation à venir.') ?></p>
            <a href="/sport_plus/terrains" class="btn-green small"><?= t('Book a Terrain →','Réserver un terrain →') ?></a>
          </div>
        <?php else: ?>
          <?php foreach (array_slice($upcoming, 0, 3) as $r): ?>
            <?php $bc = $cityClass[$r['localisation']] ?? ''; $sl = getLang()==='FR' ? ($statusLabelFR[$r['statut']]??$r['statut']) : ($statusLabel[$r['statut']]??$r['statut']); ?>
            <div class="reservation-card">
              <div class="res-icon"><?= $sportEmoji[$r['type_sport']] ?? '🏟️' ?></div>
              <div class="res-info">
                <div class="res-title"><?= e($r['terrain_name']) ?></div>
                <div class="res-meta">
                  <span class="city-badge <?= $bc ?>"><?= e($r['localisation']) ?></span>
                  <span><?= date('D d M', strtotime($r['date'])) ?> · <?= substr($r['heure_debut'],0,5) ?>–<?= substr($r['heure_fin'],0,5) ?></span>
                  <span style="color:var(--green);font-weight:700"><?= fmtPrice($r['prix']) ?></span>
                </div>
              </div>
              <div class="res-actions">
                <span class="badge <?= $statusClass[$r['statut']] ?? 'badge-gray' ?>"><?= $sl ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- RESERVATIONS TAB -->
    <div class="tab-content" id="tab-reservations">
      <div class="dash-header">
        <h1><?= t('My Reservations','Mes Réservations') ?></h1>
        <p><?= t('All your bookings.','Toutes vos réservations.') ?></p>
      </div>
      <?php if (empty($reservations)): ?>
        <div class="empty-state">
          <p style="margin-bottom:12px">📭 <?= t('No reservations yet.','Aucune réservation pour l\'instant.') ?></p>
          <a href="/sport_plus/terrains" class="btn-green small"><?= t('Book a Terrain →','Réserver →') ?></a>
        </div>
      <?php else: ?>
        <?php foreach ($reservations as $r): ?>
          <?php $bc = $cityClass[$r['localisation']] ?? ''; $sl = getLang()==='FR' ? ($statusLabelFR[$r['statut']]??$r['statut']) : ($statusLabel[$r['statut']]??$r['statut']); ?>
          <div class="reservation-card">
            <div class="res-icon"><?= $sportEmoji[$r['type_sport']] ?? '🏟️' ?></div>
            <div class="res-info">
              <div class="res-title"><?= e($r['terrain_name']) ?></div>
              <div class="res-meta">
                <span class="city-badge <?= $bc ?>"><?= e($r['localisation']) ?></span>
                <span><?= date('D d M Y', strtotime($r['date'])) ?> · <?= substr($r['heure_debut'],0,5) ?>–<?= substr($r['heure_fin'],0,5) ?></span>
                <span style="color:var(--green);font-weight:700"><?= fmtPrice($r['prix']) ?></span>
              </div>
            </div>
            <div class="res-actions">
              <span class="badge <?= $statusClass[$r['statut']] ?? 'badge-gray' ?>"><?= $sl ?></span>
              <?php if (in_array($r['statut'],['pending','confirmed']) && $r['date'] >= $today): ?>
                <form method="post" action="/sport_plus/reservation/cancel" onsubmit="return confirm('<?= t('Cancel this reservation?','Annuler cette réservation ?') ?>')">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>"/>
                  <button type="submit" class="action-btn red" style="background:none;border:1px solid rgba(255,80,80,.35);color:#ff7070;padding:5px 12px;border-radius:6px;font-size:12px;cursor:pointer">
                    <?= t('Cancel','Annuler') ?>
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- PROFILE TAB -->
    <div class="tab-content" id="tab-profile">
      <div class="dash-header">
        <h1><?= t('My Profile','Mon Profil') ?></h1>
        <p><?= t('Update your personal information.','Modifiez vos informations personnelles.') ?></p>
      </div>
      <?php $profileSuccess = flash('profile_success'); if ($profileSuccess): ?>
        <div class="alert alert-success" style="max-width:480px"><?= e($profileSuccess) ?></div>
      <?php endif; ?>
      <form method="post" action="/sport_plus/profile/update" class="profile-form-max">
        <div class="form-group">
          <label><?= t('Full Name','Nom complet') ?></label>
          <input type="text" name="nom" value="<?= e($user['nom'] ?? '') ?>" placeholder="<?= t('Your name','Votre nom') ?>" required/>
        </div>
        <div class="form-group">
          <label><?= t('Email Address','Adresse email') ?></label>
          <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" placeholder="you@example.com" required/>
        </div>
        <button type="submit" class="btn-green"><?= t('Save Changes','Enregistrer') ?></button>
      </form>
    </div>

    <!-- HISTORY TAB -->
    <div class="tab-content" id="tab-history">
      <div class="dash-header">
        <h1><?= t('Booking History','Historique des réservations') ?></h1>
        <p><?= t('Your complete booking record.','Votre historique complet.') ?></p>
      </div>
      <?php if (empty($reservations)): ?>
        <div class="empty-state"><p>📭 <?= t('No bookings yet.','Aucune réservation.') ?></p></div>
      <?php else: ?>
        <div class="admin-full-card">
          <table class="data-table">
            <thead>
              <tr>
                <th><?= t('Terrain','Terrain') ?></th>
                <th><?= t('City','Ville') ?></th>
                <th><?= t('Date','Date') ?></th>
                <th><?= t('Time','Heure') ?></th>
                <th><?= t('Amount','Montant') ?></th>
                <th><?= t('Status','Statut') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservations as $r): ?>
                <?php $bc = $cityClass[$r['localisation']] ?? ''; $sl = getLang()==='FR' ? ($statusLabelFR[$r['statut']]??$r['statut']) : ($statusLabel[$r['statut']]??$r['statut']); ?>
                <tr>
                  <td><?= e($r['terrain_name']) ?></td>
                  <td><span class="city-badge <?= $bc ?>"><?= e($r['localisation']) ?></span></td>
                  <td><?= date('d M Y', strtotime($r['date'])) ?></td>
                  <td><?= substr($r['heure_debut'],0,5) ?>–<?= substr($r['heure_fin'],0,5) ?></td>
                  <td><strong style="color:var(--green)"><?= fmtPrice($r['prix']) ?></strong></td>
                  <td><span class="badge <?= $statusClass[$r['statut']] ?? 'badge-gray' ?>"><?= $sl ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- SETTINGS TAB -->
    <div class="tab-content" id="tab-settings">
      <div class="dash-header">
        <h1><?= t('Settings','Paramètres') ?></h1>
        <p><?= t('Customize your experience.','Personnalisez votre expérience.') ?></p>
      </div>
      <div class="settings-grid">
        <!-- Language -->
        <div class="settings-card">
          <div class="settings-title">🌐 <?= t('Language','Langue') ?></div>
          <div class="toggle-group">
            <button class="toggle-btn <?= getLang()==='EN'?'active':'' ?>" onclick="setPref('pref_lang','EN',this)">🇬🇧 EN</button>
            <button class="toggle-btn <?= getLang()==='FR'?'active':'' ?>" onclick="setPref('pref_lang','FR',this)">🇫🇷 FR</button>
          </div>
        </div>
        <!-- Currency -->
        <div class="settings-card">
          <div class="settings-title">💱 <?= t('Currency','Devise') ?></div>
          <div class="toggle-group">
            <button class="toggle-btn <?= getCurrency()==='MAD'?'active':'' ?>" onclick="setPref('pref_currency','MAD',this)">MAD</button>
            <button class="toggle-btn <?= getCurrency()==='EUR'?'active':'' ?>" onclick="setPref('pref_currency','EUR',this)">EUR €</button>
          </div>
          <p style="font-size:12px;color:var(--gray);margin-top:10px"><?= t('Rate: 10 MAD = 1 €','Taux: 10 MAD = 1 €') ?></p>
        </div>
        <!-- Theme -->
        <div class="settings-card">
          <div class="settings-title">🎨 <?= t('Theme','Thème') ?></div>
          <div class="toggle-group">
            <button class="toggle-btn <?= getTheme()==='dark'?'active':'' ?>" onclick="setPref('pref_theme','dark',this)">🌙 <?= t('Dark','Sombre') ?></button>
            <button class="toggle-btn <?= getTheme()==='light'?'active':'' ?>" onclick="setPref('pref_theme','light',this)">☀️ <?= t('Light','Clair') ?></button>
          </div>
        </div>
      </div>
    </div>

  </main>
</div>

<!-- Booking Confirmed Modal -->
<?php if ($bookingMsg): ?>
<div class="modal-overlay show" id="bookingModal">
  <div class="modal-box">
    <div class="modal-icon">✅</div>
    <h2><?= t('Booking Confirmed!','Réservation confirmée !') ?></h2>
    <p><?= e($bookingMsg) ?></p>
    <button class="btn-green" onclick="closeModal('bookingModal','reservations')" style="width:100%;justify-content:center">
      <?= t('View My Reservations','Voir mes réservations') ?>
    </button>
  </div>
</div>
<?php endif; ?>

<!-- Booking Error Modal -->
<?php if ($errorMsg): ?>
<div class="modal-overlay show" id="errorModal">
  <div class="modal-box">
    <div class="modal-icon">❌</div>
    <h2><?= t('Booking Failed','Échec de la réservation') ?></h2>
    <p><?= e($errorMsg) ?></p>
    <button class="btn-green" onclick="document.getElementById('errorModal').classList.remove('show')" style="width:100%;justify-content:center">
      <?= t('Close','Fermer') ?>
    </button>
  </div>
</div>
<?php endif; ?>

<!-- FAQ Modal -->
<div class="modal-overlay" id="faqModal">
  <div class="modal-box modal-box-left" style="max-width:560px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px">
      <h2>💬 <?= t('Help & FAQ','Aide & FAQ') ?></h2>
      <button onclick="document.getElementById('faqModal').classList.remove('show')" style="background:none;border:none;color:var(--gray);font-size:26px;cursor:pointer;line-height:1">×</button>
    </div>
    <div class="faq-list">
      <?php
      $faqs = [
        [t('How do I book a terrain?','Comment réserver un terrain ?'),
         t('Go to Terrains, pick one, choose a date and available time slot, then click Confirm Booking.','Rendez-vous sur Terrains, choisissez-en un, sélectionnez une date et un créneau, puis cliquez sur Confirmer.')],
        [t('Can I cancel a reservation?','Puis-je annuler une réservation ?'),
         t('Yes — open My Reservations and click Cancel on any pending or confirmed future booking.','Oui, ouvrez Mes réservations et cliquez sur Annuler pour toute réservation future confirmée ou en attente.')],
        [t('When is my booking confirmed?','Quand ma réservation est-elle confirmée ?'),
         t('Bookings start as Pending. An admin reviews and confirms them. You can see the status in your dashboard.','Les réservations démarrent en attente. Un admin les confirme. Vous pouvez voir le statut dans votre tableau de bord.')],
        [t('How do I change my name or email?','Comment changer mon nom ou email ?'),
         t('Go to the Profile tab in your dashboard and update your details, then click Save Changes.','Rendez-vous dans l\'onglet Profil de votre tableau de bord, modifiez vos informations et cliquez sur Enregistrer.')],
        [t('Can I switch currency display?','Puis-je changer l\'affichage de la devise ?'),
         t('Yes! Open the Settings tab and toggle between MAD and EUR (10 MAD ≈ 1 €).','Oui ! Ouvrez l\'onglet Paramètres et basculez entre MAD et EUR (10 MAD ≈ 1 €).')],
        [t('How do I leave a review?','Comment laisser un avis ?'),
         t('Visit any terrain detail page, scroll to Reviews, fill in the form and click Post Review.','Visitez la page d\'un terrain, faites défiler jusqu\'aux avis, remplissez le formulaire et cliquez sur Publier.')],
      ];
      foreach ($faqs as [$q, $a]):
      ?>
        <div class="faq-item">
          <div class="faq-q" onclick="toggleFaq(this)"><?= $q ?></div>
          <div class="faq-a"><?= $a ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Floating FAQ Button -->
<button class="faq-btn" onclick="document.getElementById('faqModal').classList.add('show')" title="<?= t('Help','Aide') ?>">?</button>

<script>
  function showTab(name, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('visible'));
    document.getElementById('tab-' + name).classList.add('visible');
    if (el) {
      document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
      el.classList.add('active');
    }
  }

  function closeModal(id, tab) {
    document.getElementById(id).classList.remove('show');
    if (tab) showTab(tab, document.querySelectorAll('.nav-item')[1]);
  }

  function setPref(key, val, btn) {
    const fd = new FormData();
    fd.append('key', key);
    fd.append('val', val);
    fetch('/sport_plus/preferences/set', {
      method: 'POST', body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(() => location.reload());
    // Optimistic UI
    btn.closest('.toggle-group').querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  }

  function toggleFaq(el) {
    el.classList.toggle('open');
    el.nextElementSibling.classList.toggle('open');
  }

  // Close modals on backdrop click
  document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
  });
</script>
</body>
</html>
