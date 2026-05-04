<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sport+ | Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/sport_plus/public/css/style.css"/>
  <style>
    body { min-height: 100vh; display: flex; flex-direction: column; }
    .auth-page {
      flex: 1;
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 100vh;
    }
    .auth-left {
      background: url('https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=900&q=80') center/cover;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 60px;
    }
    .auth-left::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.3) 60%, transparent 100%);
    }
    .auth-left-content { position: relative; z-index: 1; }
    .auth-left h2 {
      font-family: var(--font-display);
      font-size: 56px;
      line-height: 0.95;
      margin-bottom: 12px;
    }
    .auth-left h2 span { color: var(--green); }
    .auth-left p { color: rgba(255,255,255,0.7); font-size: 16px; }

    .auth-right {
      background: var(--bg2);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px;
    }
    .auth-box { width: 100%; max-width: 420px; }
    .auth-box .logo { display: block; margin-bottom: 40px; font-size: 22px; }

    .auth-tabs { display: flex; gap: 0; margin-bottom: 36px; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .auth-tab {
      flex: 1;
      text-align: center;
      padding: 14px;
      font-size: 15px;
      font-weight: 600;
      color: var(--gray);
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all .2s;
      background: none;
      border-top: none;
      border-left: none;
      border-right: none;
      font-family: var(--font-body);
    }
    .auth-tab.active { color: var(--green); border-bottom-color: var(--green); }

    .auth-form { display: none; }
    .auth-form.visible { display: block; }

    .auth-form h3 { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
    .auth-form .sub { color: var(--gray); font-size: 14px; margin-bottom: 28px; }

    .divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; color: var(--gray); font-size: 13px; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.08); }

    .forgot { float: right; font-size: 13px; color: var(--green); cursor: pointer; }

    .terms { font-size: 12px; color: var(--gray); text-align: center; margin-top: 16px; line-height: 1.6; }
    .terms a { color: var(--green); }

    .sport-select { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 8px; }
    .sport-opt {
      padding: 10px 6px;
      text-align: center;
      border: 1.5px solid var(--gray2);
      border-radius: 10px;
      font-size: 12px;
      cursor: pointer;
      transition: all .2s;
      background: none;
      color: var(--gray);
      font-family: var(--font-body);
    }
    .sport-opt:hover { border-color: var(--green); color: var(--white); }
    .sport-opt.selected { border-color: var(--green); background: rgba(76,255,114,0.1); color: var(--green); }
    .sport-opt .emoji { font-size: 20px; display: block; margin-bottom: 4px; }

    @media (max-width: 768px) {
      .auth-page { grid-template-columns: 1fr; }
      .auth-left { display: none; }
      .auth-right { padding: 40px 24px; padding-top: 100px; }
    }
    .google-btn {
  width:100%;
  padding:14px;
  border-radius:40px;
  border:1px solid #333;
  background:transparent;
  color:white;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
}

.google-btn img {
  width:18px;
}
  </style>
</head>
<body>

<nav class="navbar">
  <a href="/sport_plus/" class="logo">SPORT<span>+</span></a>
  <ul class="nav-links">
    <li><a href="/sport_plus/">Home</a></li>
    <li><a href="/sport_plus/terrains">Terrains</a></li>
    <li><a href="/sport_plus/#about-us">About Us</a></li>
  </ul>
  <div class="nav-actions">
    <a href="/sport_plus/login" class="btn-ghost">Login</a>
    <a href="/sport_plus/terrains" class="btn-green">Book Now</a>
  </div>
  <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('open')">&#9776;</button>
</nav>

<div class="auth-page">
  <!-- LEFT: Visual -->
  <div class="auth-left">
    <div class="auth-left-content">
      <h2>PLAY.<br/>BOOK.<br/><span>WIN.</span></h2>
      <p>Join thousands of players booking<br/>sports terrains every day.</p>
    </div>
  </div>

  <!-- RIGHT: Forms -->
  <div class="auth-right">
    <div class="auth-box">
      <a href="/sport_plus/" class="logo">SPORT<span>+</span></a>

      <div class="auth-tabs">
        <button class="auth-tab active" onclick="switchTab('login', this)">Login</button>
        <button class="auth-tab" onclick="switchTab('register', this)">Sign Up</button>
      </div>

      <?php $flashError = flash('error'); $flashSuccess = flash('success'); ?>
      <?php if ($flashError): ?>
        <div style="background:rgba(255,80,80,.15);border:1px solid rgba(255,80,80,.4);color:#ff7070;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px"><?= e($flashError) ?></div>
      <?php endif; ?>
      <?php if ($flashSuccess): ?>
        <div style="background:rgba(76,255,114,.1);border:1px solid rgba(76,255,114,.3);color:#4cff72;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px"><?= e($flashSuccess) ?></div>
      <?php endif; ?>

      <!-- LOGIN FORM -->
      <form class="auth-form visible" id="loginForm" action="/sport_plus/login/submit" method="post">
        <h3>Welcome back</h3>
        <p class="sub">Sign in to your Sport+ account</p>

        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="you@example.com" required/>
        </div>
        <div class="form-group">
          <label>Password <span class="forgot">Forgot password?</span></label>
          <input type="password" name="password" placeholder="Password" required/>
        </div>

        <button type="submit" class="btn-green" style="width:100%;justify-content:center;margin-top:8px">
          Login →
        </button>

        <div class="divider">or continue with</div>
        <button type="button" class="google-btn">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg">
            Continue with Google
        </button>

        <p class="terms">Don't have an account? <a href="#" onclick="switchTabByName('register')">Sign up for free</a></p>
      </form>

      <!-- REGISTER FORM -->
      <form class="auth-form" id="registerForm" action="/sport_plus/register" method="post">
        <h3>Create account</h3>
        <p class="sub">Start booking in under 2 minutes</p>

        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="nom" placeholder="Ahmed Benali" required/>
        </div>

        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="you@example.com" required/>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Min. 8 characters" required/>
        </div>

        <div class="form-group">
          <label>Favourite Sport</label>
          <div class="sport-select">
            <button type="button" class="sport-opt selected" onclick="selectSport(this)"><span class="emoji">⚽</span>Football</button>
            <button type="button" class="sport-opt" onclick="selectSport(this)"><span class="emoji">🎾</span>Tennis</button>
            <button type="button" class="sport-opt" onclick="selectSport(this)"><span class="emoji">🏓</span>Padel</button>
            <button type="button" class="sport-opt" onclick="selectSport(this)"><span class="emoji">🏀</span>Basketball</button>
          </div>
        </div>

        <button type="submit" class="btn-green" style="width:100%;justify-content:center;margin-top:8px">
          Create Account →
        </button>

        <p class="terms">By signing up, you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.</p>
        <p class="terms" style="margin-top:8px">Already have an account? <a href="#" onclick="switchTabByName('login')">Login</a></p>
      </form>
    </div>
  </div>
</div>

<script>
  function switchTab(tab, btn) {
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('visible'));
    btn.classList.add('active');
    document.getElementById(tab + 'Form').classList.add('visible');
  }
  function switchTabByName(tab) {
    const tabs = document.querySelectorAll('.auth-tab');
    tabs.forEach((t, i) => {
      if ((tab === 'login' && i === 0) || (tab === 'register' && i === 1)) {
        t.click();
      }
    });
  }
  function selectSport(btn) {
    btn.closest('.sport-select').querySelectorAll('.sport-opt').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
  }
</script>
</body>
</html>
