<?php
session_start();
// If already logged in, send straight to homepage — no back button can return here
if (isset($_SESSION['user_id'])) {
    header('Location: homepage.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In — NestledBrew</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
  <link rel="stylesheet" href="login.css">
</head>
<body>

  <!-- Page Loader -->
  <div class="page-loader" id="pageLoader">
    <div class="loader-inner">
      <div class="loader-logo">N</div>
      <div class="loader-bar"><div class="loader-fill"></div></div>
    </div>
  </div>

  <div class="toast" id="globalToast"></div>

  <!-- Minimal Nav -->
  <header id="mainHeader">
    <div class="nav-main" style="justify-content: space-between;">
      <a href="homepage.php" class="brand-link">
        <div class="logo-mark">
          <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="20" cy="20" r="18" stroke="currentColor" stroke-width="1.5"/>
            <text x="50%" y="56%" dominant-baseline="middle" text-anchor="middle" font-family="Fraunces, serif" font-size="16" font-style="italic" fill="currentColor">N</text>
          </svg>
        </div>
        <span class="brand-text">NestledBrew</span>
      </a>
      <div class="nav-topstrip-actions">
        <button onclick="toggleMode()" id="modeBtn">☀ Light Mode</button>
      </div>
    </div>
  </header>

  <div class="auth-grain"></div>

  <div class="auth-page">
    <div class="auth-container">

      <!-- LEFT: Brand Panel -->
      <div class="auth-panel">
        <div class="auth-panel-brand">
          <div class="auth-panel-logo">
            <svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="26" cy="26" r="24" stroke="currentColor" stroke-width="1.5"/>
              <circle cx="26" cy="26" r="18" stroke="currentColor" stroke-width="1" opacity="0.4"/>
              <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="Fraunces, serif" font-size="22" font-style="italic" fill="currentColor">N</text>
            </svg>
          </div>
          <div class="auth-panel-name">NestledBrew</div>
          <div class="auth-panel-tagline">
            Join our community of coffee lovers. Every cup earns you more.
          </div>
        </div>

        <div class="auth-panel-perks">
          <div class="auth-perk">
            <div class="auth-perk-icon">☕</div>
            <div class="auth-perk-text">
              <strong>Earn Rewards Points</strong>
              <span>Get points with every order and unlock exclusive perks</span>
            </div>
          </div>
          <div class="auth-perk">
            <div class="auth-perk-icon">🎂</div>
            <div class="auth-perk-text">
              <strong>Free Birthday Drink</strong>
              <span>A complimentary drink of your choice on your birthday</span>
            </div>
          </div>
          <div class="auth-perk">
            <div class="auth-perk-icon">✨</div>
            <div class="auth-perk-text">
              <strong>Early Menu Access</strong>
              <span>Be the first to try new seasonal drinks before anyone else</span>
            </div>
          </div>
          <div class="auth-perk">
            <div class="auth-perk-icon">🚀</div>
            <div class="auth-perk-text">
              <strong>Priority Queuing</strong>
              <span>Skip the wait during rush hour with member queuing</span>
            </div>
          </div>
        </div>

        <div class="auth-panel-footer">
          © 2026 NestledBrew · Made with ☕ in Cebu City
        </div>
      </div>

      <!-- RIGHT: Form Panel -->
      <div class="auth-form-panel">
        <div class="auth-tabs">
          <button class="auth-tab active" id="signinTabBtn" onclick="switchAuthTab('signin')">Sign In</button>
          <button class="auth-tab" id="signupTabBtn" onclick="switchAuthTab('signup')">Create Account</button>
        </div>

        <!-- SIGN IN FORM -->
        <div class="auth-form" id="signinForm">
          <div style="margin-bottom:4px">
            <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:600;font-style:italic;color:var(--text);margin-bottom:6px;">Welcome back</div>
            <div style="font-family:var(--font-body);font-size:0.9rem;font-weight:300;color:var(--text-muted);">Sign in to your NestledBrew account</div>
          </div>

          <div class="form-group">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0" for="signinIdentifier">Email or First Name</label>
              <button type="button" onclick="toggleLoginType()"
                style="font-family:var(--font-body);font-size:0.75rem;color:var(--accent);background:none;border:none;cursor:pointer;padding:0;"
                id="loginTypeToggle">Use first name instead</button>
            </div>
            <input type="text" class="form-input" id="signinIdentifier" name="identifier"
              placeholder="your@email.com" autocomplete="email"
              onkeydown="if(event.key==='Enter') handleSignIn()">
          </div>
          <div class="form-group">
            <label class="form-label" for="signinPassword">Password</label>
            <input type="password" class="form-input" id="signinPassword" name="password"
              placeholder="••••••••" autocomplete="current-password"
              onkeydown="if(event.key==='Enter') handleSignIn()">
          </div>
          <div class="form-forgot">
            <button onclick="showToast('Password reset link sent to your email!')">Forgot password?</button>
          </div>

          <button class="form-submit" id="signinBtn" onclick="handleSignIn()">Sign In</button>

          <div class="form-divider">or</div>

          <div class="form-note">
            Don't have an account? <button onclick="switchAuthTab('signup')">Create one</button>
          </div>
        </div>

        <!-- SIGN UP FORM -->
        <div class="auth-form hidden" id="signupForm">
          <div style="margin-bottom:4px">
            <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:600;font-style:italic;color:var(--text);margin-bottom:6px;">Join NestledBrew</div>
            <div style="font-family:var(--font-body);font-size:0.9rem;font-weight:300;color:var(--text-muted);">Create your free account and start earning rewards</div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="signupFirst">First Name</label>
              <input type="text" class="form-input" id="signupFirst" name="first_name"
                placeholder="Juan" autocomplete="given-name">
            </div>
            <div class="form-group">
              <label class="form-label" for="signupLast">Last Name</label>
              <input type="text" class="form-input" id="signupLast" name="last_name"
                placeholder="Dela Cruz" autocomplete="family-name">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" for="signupEmail">Email Address</label>
            <input type="email" class="form-input" id="signupEmail" name="email"
              placeholder="your@email.com" autocomplete="email">
          </div>
          <div class="form-group">
            <label class="form-label" for="signupPassword">Password</label>
            <input type="password" class="form-input" id="signupPassword" name="password"
              placeholder="Min. 6 characters" autocomplete="new-password"
              onkeydown="if(event.key==='Enter') handleSignUp()">
          </div>

          <button class="form-submit" id="signupBtn" onclick="handleSignUp()">Create Account — It's Free</button>

          <div class="form-note">
            Already have an account? <button onclick="switchAuthTab('signin')">Sign in</button>
          </div>
        </div>

        <!-- SUCCESS STATE -->
        <div class="auth-success" id="authSuccess">
          <div class="success-icon">☕</div>
          <div class="success-title" id="successTitle">Welcome!</div>
          <div class="success-msg" id="successMsg"></div>
          <a href="homepage.php" class="btn btn-primary btn-lg" style="margin-top:8px">Go to Homepage</a>
        </div>
      </div>
    </div>
  </div>


  <script src="homepage.js"></script>
  <script>
    // Back-button lock
    // Replace current history entry so back goes to wherever the user
    // came from before login, not back to this page after success.
    history.replaceState({ page: 'login' }, '', location.href);

    window.addEventListener('pageshow', function (e) {
      // bfcache restoration — if we somehow end up here after login, redirect
      if (e.persisted) {
        fetch('check_session.php')
          .then(r => r.json())
          .then(d => { if (d.logged_in) window.location.replace('homepage.php'); })
          .catch(() => {});
      }
    });

    /* ===== Tab switching ===== */
    if (window.location.hash === '#signup') {
      switchAuthTab('signup');
    }

    function switchAuthTab(tab) {
      document.getElementById('signinForm').classList.toggle('hidden', tab !== 'signin');
      document.getElementById('signupForm').classList.toggle('hidden', tab !== 'signup');
      document.getElementById('signinTabBtn').classList.toggle('active', tab === 'signin');
      document.getElementById('signupTabBtn').classList.toggle('active', tab === 'signup');
    }

    function showSuccess(title, msg) {
      document.getElementById('signinForm').classList.add('hidden');
      document.getElementById('signupForm').classList.add('hidden');
      document.querySelectorAll('.auth-tab').forEach(t => t.style.opacity = '0.3');
      document.getElementById('successTitle').textContent = title;
      document.getElementById('successMsg').textContent   = msg;
      document.getElementById('authSuccess').classList.add('visible');
    }

    /* ===== Sign In ===== */
    async function handleSignIn() {
      const identifier = document.getElementById('signinIdentifier').value.trim();
      const email      = identifier; // sent as-is, server handles both
      const password = document.getElementById('signinPassword').value;

      if (!email || !password) { showToast('Please fill in all fields.'); return; }

      const btn = document.getElementById('signinBtn');
      btn.textContent = 'Signing in…';
      btn.disabled = true;

      try {
        const fd = new FormData();
        fd.append('identifier', identifier);
        fd.append('password', password);

        const res    = await fetch('login_process.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result.status === 'success') {
          // Show rewards points earned in a toast before redirecting
          const pts = result.points ?? 0;
          showToast(`☕ Welcome back, ${result.name}! You have ${pts} BrewPoints.`, 3000);

          showSuccess(
            `Welcome back, ${result.name}! ☕`,
            `You're signed in. You currently have ${pts} BrewPoints. Redirecting…`
          );

          setTimeout(() => {
            // Replace so back button skips this page entirely
            window.location.replace('homepage.php');
          }, 2500);
        } else {
          showToast(result.message);
          btn.textContent = 'Sign In';
          btn.disabled = false;
        }
      } catch (err) {
        console.error(err);
        showToast('Connection error. Is XAMPP running?');
        btn.textContent = 'Sign In';
        btn.disabled = false;
      }

      let loginByName = false;
        function toggleLoginType() {
          loginByName = !loginByName;
          const input  = document.getElementById('signinIdentifier');
          const toggle = document.getElementById('loginTypeToggle');
          if (loginByName) {
            input.type        = 'text';
            input.placeholder = 'Your first name';
            input.autocomplete = 'given-name';
            toggle.textContent = 'Use email instead';
          } else {
            input.type        = 'text';
            input.placeholder = 'your@email.com';
            input.autocomplete = 'email';
            toggle.textContent = 'Use first name instead';
          }
        }
    }

    /* ===== Sign Up ===== */
    async function handleSignUp() {
      const first    = document.getElementById('signupFirst').value.trim();
      const last     = document.getElementById('signupLast').value.trim();
      const email    = document.getElementById('signupEmail').value.trim();
      const password = document.getElementById('signupPassword').value;

      if (!first || !last || !email || !password) { showToast('Please fill in all fields.'); return; }
      if (password.length < 6) { showToast('Password must be at least 6 characters.'); return; }

      const btn = document.getElementById('signupBtn');
      btn.textContent = 'Creating account…';
      btn.disabled = true;

      try {
        const fd = new FormData();
        fd.append('first_name', first);
        fd.append('last_name',  last);
        fd.append('email',      email);
        fd.append('password',   password);

        const res    = await fetch('signup_process.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result.status === 'success') {
          // Prompt about rewards on sign-up too
          showToast('🎉 Account created! Start earning BrewPoints with your first order.', 4000);

          showSuccess(
            `Welcome, ${first}! 🎉`,
            `Your NestledBrew account is ready. You've been enrolled in Rewards — start at Bean Starter with 0 points. Go earn some!`
          );

          setTimeout(() => {
            window.location.replace('rewards.php');
          }, 3500);
        } else {
          showToast(result.message || 'Registration failed.');
          btn.textContent = 'Create Account — It\'s Free';
          btn.disabled = false;
        }
      } catch (err) {
        console.error(err);
        showToast('Server connection error.');
        btn.textContent = 'Create Account — It\'s Free';
        btn.disabled = false;
      }
    }

    function toggleMode() {
      const isLight = document.body.classList.contains('light-mode');
      applyMode(!isLight);
    }
  </script>
</body>
</html>