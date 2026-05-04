<?php
// Variables: $stats, $byCity, $recent, $terrains, $reservations, $users, $activeTab
$statusClass = ['pending'=>'badge-yellow','confirmed'=>'badge-green','cancelled'=>'badge-red','completed'=>'badge-gray'];
$statusLabel = ['pending'=>'Pending','confirmed'=>'Confirmed','cancelled'=>'Cancelled','completed'=>'Completed'];
$cityClass   = ['Marrakesh'=>'marrakesh','Casablanca'=>'casa','Tanger'=>'','Tangier'=>''];
$flashMsg    = flash('success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sport+ | Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/sport_plus/public/css/style.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    body { background:#0a0a0a; }
    .admin-layout { display:grid; grid-template-columns:240px 1fr; min-height:100vh; }
    .admin-sidebar { background:#0d0d0d; border-right:1px solid rgba(255,255,255,.06); height:100vh; position:sticky; top:0; display:flex; flex-direction:column; overflow-y:auto; }
    .admin-logo { padding:24px; border-bottom:1px solid rgba(255,255,255,.06); }
    .admin-logo .logo { font-size:20px; }
    .admin-logo span { display:block; color:var(--green); font-size:10px; letter-spacing:2px; text-transform:uppercase; }
    .admin-nav-label { font-size:9px; letter-spacing:2px; text-transform:uppercase; color:var(--gray); padding:20px 20px 8px; }
    .admin-nav-item { padding:12px 20px; color:var(--gray); cursor:pointer; border-left:3px solid transparent; transition:.15s; }
    .admin-nav-item:hover, .admin-nav-item.active { color:var(--green); background:rgba(76,255,114,.06); border-left-color:var(--green); }
    .admin-user { margin-top:auto; padding:16px 20px; border-top:1px solid rgba(255,255,255,.06); }
    .admin-main { padding:36px; overflow-x:auto; }
    .admin-topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; gap:16px; flex-wrap:wrap; }
    .admin-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:16px; margin-bottom:28px; }
    .admin-stat-card,.admin-card,.admin-full-card,.city-admin-card { background:var(--card); border:1px solid rgba(255,255,255,.05); border-radius:var(--radius); overflow:hidden; }
    .admin-stat-card { padding:22px; }
    .asc-val { font-family:var(--font-display); font-size:38px; color:var(--green); line-height:1; }
    .asc-label { font-size:12px; color:var(--gray); text-transform:uppercase; letter-spacing:1px; }
    .admin-content-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:28px; }
    .city-admin-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px; }
    .city-admin-card { padding:20px; }
    .city-admin-card strong { font-family:var(--font-display); font-size:34px; color:var(--green); display:block; }
    .admin-card-header { padding:18px 20px; border-bottom:1px solid rgba(255,255,255,.05); display:flex; justify-content:space-between; align-items:center; }
    .admin-card-body { padding:0; }
    .tab-content { display:none; }
    .tab-content.visible { display:block; }
    .action-btn { background:none; border:1px solid var(--gray2); color:var(--gray); padding:5px 12px; border-radius:6px; font-size:12px; cursor:pointer; margin-left:6px; }
    .action-btn.confirm-btn { border-color:rgba(76,255,114,.4); color:var(--green); }
    .action-btn.delete-btn { border-color:rgba(255,80,80,.4); color:#ff7070; }
    /* Add terrain modal */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.8); z-index:9999; align-items:center; justify-content:center; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:var(--bg2); border:1px solid rgba(255,255,255,.1); border-radius:var(--radius-lg); padding:36px; max-width:480px; width:90%; }
    .modal-box h2 { margin-bottom:24px; }
    @media(max-width:900px){.admin-layout{grid-template-columns:1fr}.admin-sidebar{display:none}.admin-content-grid,.city-admin-grid{grid-template-columns:1fr}.admin-main{padding:20px}}
  </style>
</head>
<body>
<div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="admin-logo">
      <div class="logo">SPORT<span style="color:var(--green)">+</span></div>
      <span>Admin Panel</span>
    </div>
    <div class="admin-nav-label">Overview</div>
    <div class="admin-nav-item <?= ($activeTab==='dashboard')?'active':'' ?>" onclick="showAdminTab('dashboard',this)">Dashboard</div>
    <div class="admin-nav-label">Management</div>
    <div class="admin-nav-item <?= ($activeTab==='terrains')?'active':'' ?>" onclick="showAdminTab('terrains',this)">Terrains</div>
    <div class="admin-nav-item <?= ($activeTab==='reservations')?'active':'' ?>" onclick="showAdminTab('reservations',this)">Reservations</div>
    <div class="admin-nav-item <?= ($activeTab==='users')?'active':'' ?>" onclick="showAdminTab('users',this)">Users</div>
    <div class="admin-user">
      <strong><?= e($_SESSION['user_nom'] ?? 'Admin') ?></strong>
      <div style="color:var(--green);font-size:12px">Super Admin</div>
      <a href="/sport_plus/logout" style="color:var(--gray);font-size:13px">Logout</a>
    </div>
  </aside>

  <main class="admin-main">

    <?php if ($flashMsg): ?>
      <div style="background:rgba(76,255,114,.1);border:1px solid rgba(76,255,114,.3);color:#4cff72;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px"><?= e($flashMsg) ?></div>
    <?php endif; ?>

    <!-- DASHBOARD TAB -->
    <div class="tab-content <?= $activeTab==='dashboard'?'visible':'' ?>" id="atab-dashboard">
      <div class="admin-topbar">
        <div><h1>Dashboard</h1><p style="color:var(--gray)">Overview of all activity</p></div>
        <a href="/sport_plus/terrains" class="btn-green small">View Site</a>
      </div>
      <div class="admin-stats">
        <div class="admin-stat-card"><div class="asc-val"><?= (int)$stats['reservations'] ?></div><div class="asc-label">Reservations</div></div>
        <div class="admin-stat-card"><div class="asc-val"><?= (int)$stats['terrains'] ?></div><div class="asc-label">Terrains</div></div>
        <div class="admin-stat-card"><div class="asc-val"><?= (int)$stats['users'] ?></div><div class="asc-label">Users</div></div>
        <div class="admin-stat-card"><div class="asc-val"><?= count($byCity) ?></div><div class="asc-label">Cities</div></div>
        <div class="admin-stat-card"><div class="asc-val"><?= number_format((float)$stats['revenue']) ?></div><div class="asc-label">Revenue MAD</div></div>
      </div>

      <?php if (!empty($byCity)): ?>
      <div class="city-admin-grid">
        <?php foreach ($byCity as $city): ?>
          <?php $bc = $cityClass[$city['localisation']] ?? ''; ?>
          <div class="city-admin-card">
            <span class="city-badge <?= $bc ?>"><?= e($city['localisation']) ?></span>
            <strong><?= (int)$city['bookings'] ?></strong>
            <p style="color:var(--gray)"><?= number_format((float)$city['revenue']) ?> MAD revenue · <?= (int)$city['terrain_count'] ?> terrains</p>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Charts row -->
      <div class="admin-content-grid" style="margin-bottom:28px">
        <div class="admin-card">
          <div class="admin-card-header"><h3>Revenue by City</h3></div>
          <div style="padding:20px"><canvas id="chartCity" height="180"></canvas></div>
        </div>
        <div class="admin-card">
          <div class="admin-card-header"><h3>Bookings — Last 7 Days</h3></div>
          <div style="padding:20px"><canvas id="chartDates" height="180"></canvas></div>
        </div>
      </div>

      <div class="admin-content-grid">
        <div class="admin-card">
          <div class="admin-card-header"><h3>Recent Reservations</h3></div>
          <table class="data-table">
            <thead><tr><th>User</th><th>Terrain</th><th>City</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              <?php foreach ($recent as $r): ?>
                <?php $bc = $cityClass[$r['localisation']] ?? ''; ?>
                <tr>
                  <td><?= e($r['user_nom']) ?></td>
                  <td><?= e($r['terrain_name']) ?></td>
                  <td><span class="city-badge <?= $bc ?>"><?= e($r['localisation']) ?></span></td>
                  <td><span class="badge <?= $statusClass[$r['statut']] ?? 'badge-gray' ?>"><?= $statusLabel[$r['statut']] ?? e($r['statut']) ?></span></td>
                  <td>
                    <?php if ($r['statut'] === 'pending'): ?>
                      <form method="post" action="/sport_plus/admin/reservation/validate" style="display:inline">
                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>"/>
                        <button type="submit" class="action-btn confirm-btn">Confirm</button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($recent)): ?>
                <tr><td colspan="5" style="text-align:center;color:var(--gray);padding:20px">No reservations yet</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="admin-card">
          <div class="admin-card-header"><h3>Terrains Overview</h3></div>
          <div class="admin-card-body">
            <?php foreach (array_slice($terrains, 0, 5) as $t): ?>
              <?php $bc = $cityClass[$t['localisation']] ?? ''; ?>
              <div style="display:flex;gap:14px;align-items:center;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.04)">
                <div style="flex:1">
                  <div style="font-weight:700"><?= e($t['nom']) ?> <span class="city-badge <?= $bc ?>"><?= e($t['localisation']) ?></span></div>
                  <div style="color:var(--gray);font-size:12px;margin-top:3px"><?= e($t['type_sport']) ?> · <?= e($t['prix']) ?> MAD/h</div>
                </div>
              </div>
            <?php endforeach; ?>
            <?php if (empty($terrains)): ?>
              <div style="padding:20px;text-align:center;color:var(--gray)">No terrains yet</div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- TERRAINS TAB -->
    <div class="tab-content <?= $activeTab==='terrains'?'visible':'' ?>" id="atab-terrains">
      <div class="admin-topbar">
        <h1>Manage Terrains</h1>
        <button class="btn-green small" onclick="document.getElementById('addTerrainModal').classList.add('show')">+ Add Terrain</button>
      </div>
      <div class="admin-full-card">
        <table class="data-table">
          <thead><tr><th>Terrain</th><th>Sport</th><th>City</th><th>Price/h</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($terrains as $t): ?>
              <?php $bc = $cityClass[$t['localisation']] ?? ''; ?>
              <tr>
                <td><?= e($t['nom']) ?></td>
                <td><?= e($t['type_sport']) ?></td>
                <td><span class="city-badge <?= $bc ?>"><?= e($t['localisation']) ?></span></td>
                <td><?= e($t['prix']) ?> MAD</td>
                <td>
                  <form method="post" action="/sport_plus/admin/terrain/delete" style="display:inline" onsubmit="return confirm('Delete this terrain?')">
                    <input type="hidden" name="id" value="<?= (int)$t['id'] ?>"/>
                    <button type="submit" class="action-btn delete-btn">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($terrains)): ?>
              <tr><td colspan="5" style="text-align:center;color:var(--gray);padding:20px">No terrains yet</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- RESERVATIONS TAB -->
    <div class="tab-content <?= $activeTab==='reservations'?'visible':'' ?>" id="atab-reservations">
      <div class="admin-topbar"><h1>All Reservations</h1></div>
      <div class="admin-full-card">
        <table class="data-table">
          <thead><tr><th>#</th><th>User</th><th>Terrain</th><th>City</th><th>Date</th><th>Time</th><th>Price</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach ($reservations as $r): ?>
              <?php $bc = $cityClass[$r['localisation']] ?? ''; ?>
              <tr>
                <td>#<?= (int)$r['id'] ?></td>
                <td><?= e($r['user_nom']) ?></td>
                <td><?= e($r['terrain_name']) ?></td>
                <td><span class="city-badge <?= $bc ?>"><?= e($r['localisation']) ?></span></td>
                <td><?= date('d M Y', strtotime($r['date'])) ?></td>
                <td><?= substr($r['heure_debut'],0,5) ?>–<?= substr($r['heure_fin'],0,5) ?></td>
                <td><?= e($r['prix']) ?> MAD</td>
                <td><span class="badge <?= $statusClass[$r['statut']] ?? 'badge-gray' ?>"><?= $statusLabel[$r['statut']] ?? e($r['statut']) ?></span></td>
                <td>
                  <?php if ($r['statut'] === 'pending'): ?>
                    <form method="post" action="/sport_plus/admin/reservation/validate" style="display:inline">
                      <input type="hidden" name="id" value="<?= (int)$r['id'] ?>"/>
                      <button type="submit" class="action-btn confirm-btn">Confirm</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($reservations)): ?>
              <tr><td colspan="9" style="text-align:center;color:var(--gray);padding:20px">No reservations yet</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- USERS TAB -->
    <div class="tab-content <?= $activeTab==='users'?'visible':'' ?>" id="atab-users">
      <div class="admin-topbar"><h1>Users</h1></div>
      <div class="admin-full-card">
        <table class="data-table">
          <thead><tr><th>Name</th><th>Email</th><th>Role</th></tr></thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td><?= e($u['nom']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><span class="badge <?= $u['role']==='admin'?'badge-green':'badge-gray' ?>"><?= e($u['role']) ?></span></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
              <tr><td colspan="3" style="text-align:center;color:var(--gray);padding:20px">No users yet</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div>

<!-- Add Terrain Modal -->
<div class="modal-overlay" id="addTerrainModal">
  <div class="modal-box">
    <h2>Add New Terrain</h2>
    <form method="post" action="/sport_plus/admin/terrain/add">
      <div class="form-group">
        <label>Terrain Name</label>
        <input type="text" name="nom" placeholder="Stade Al Majd" required/>
      </div>
      <div class="form-group">
        <label>Sport Type</label>
        <select name="type_sport" required>
          <option value="Football">Football</option>
          <option value="Tennis">Tennis</option>
          <option value="Padel">Padel</option>
          <option value="Basketball">Basketball</option>
        </select>
      </div>
      <div class="form-group">
        <label>City</label>
        <select name="localisation" required>
          <option value="Tanger">Tanger</option>
          <option value="Marrakesh">Marrakesh</option>
          <option value="Casablanca">Casablanca</option>
        </select>
      </div>
      <div class="form-group">
        <label>Price per hour (MAD)</label>
        <input type="number" name="prix" placeholder="120" min="1" required/>
      </div>
      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="btn-green">Add Terrain</button>
        <button type="button" class="btn-ghost" onclick="document.getElementById('addTerrainModal').classList.remove('show')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  function showAdminTab(name, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('visible'));
    document.getElementById('atab-' + name).classList.add('visible');
    document.querySelectorAll('.admin-nav-item').forEach(n => n.classList.remove('active'));
    if (el) el.classList.add('active');
  }
  document.getElementById('addTerrainModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('show');
  });

  // ---- Chart.js ----
  const chartDefaults = {
    color: '#888',
    font: { family: 'Inter', size: 12 },
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { color: 'rgba(255,255,255,.05)' }, ticks: { color: '#888' } },
      y: { grid: { color: 'rgba(255,255,255,.05)' }, ticks: { color: '#888' }, beginAtZero: true }
    }
  };

  // Revenue by city bar chart
  <?php
    $cityLabels  = array_column($byCity, 'localisation');
    $cityRevenue = array_map(fn($c) => (float)$c['revenue'], $byCity);
    $cityBookings = array_map(fn($c) => (int)$c['bookings'], $byCity);
  ?>
  new Chart(document.getElementById('chartCity'), {
    type: 'bar',
    data: {
      labels: <?= json_encode($cityLabels) ?>,
      datasets: [
        {
          label: 'Revenue (MAD)',
          data: <?= json_encode($cityRevenue) ?>,
          backgroundColor: 'rgba(76,255,114,0.7)',
          borderRadius: 6,
          borderSkipped: false,
        },
        {
          label: 'Bookings',
          data: <?= json_encode($cityBookings) ?>,
          backgroundColor: 'rgba(76,160,255,0.55)',
          borderRadius: 6,
          borderSkipped: false,
          yAxisID: 'y2',
        }
      ]
    },
    options: {
      ...chartDefaults,
      plugins: {
        legend: { display: true, labels: { color: '#888', font: { size: 12 } } },
        tooltip: { mode: 'index' }
      },
      scales: {
        x:  { grid: { color: 'rgba(255,255,255,.05)' }, ticks: { color: '#888' } },
        y:  { grid: { color: 'rgba(255,255,255,.05)' }, ticks: { color: '#4cff72' }, beginAtZero: true },
        y2: { position: 'right', grid: { drawOnChartArea: false }, ticks: { color: '#4ca0ff' }, beginAtZero: true }
      }
    }
  });

  // Bookings by date line chart (last 7 days)
  <?php
    $dateLabels = array_column($bookingsByDate, 'booking_date');
    $dateCounts = array_map(fn($d) => (int)$d['count'], $bookingsByDate);
    // Fill missing days with 0
    $allDays = []; $allCounts = [];
    for ($i = 6; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime("-$i days"));
        $allDays[] = date('d M', strtotime($day));
        $idx = array_search($day, $dateLabels);
        $allCounts[] = $idx !== false ? $dateCounts[$idx] : 0;
    }
  ?>
  new Chart(document.getElementById('chartDates'), {
    type: 'line',
    data: {
      labels: <?= json_encode($allDays) ?>,
      datasets: [{
        label: 'Bookings',
        data: <?= json_encode($allCounts) ?>,
        borderColor: '#4cff72',
        backgroundColor: 'rgba(76,255,114,0.08)',
        tension: 0.4,
        pointBackgroundColor: '#4cff72',
        pointRadius: 5,
        fill: true,
      }]
    },
    options: {
      ...chartDefaults,
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ctx.parsed.y + ' booking' + (ctx.parsed.y !== 1 ? 's' : '') } }
      }
    }
  });
</script>
</body>
</html>
