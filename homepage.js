/* =========================================
   NestledBrew — homepage.js
   ========================================= */

/* ===== PAGE LOADER ===== */
window.addEventListener('load', () => {
  setTimeout(() => {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.classList.add('hidden');
  }, 1400);
});

/* ===== DARK / LIGHT MODE ===== */
function applyMode(isLight) {
  document.body.classList.toggle('light-mode', isLight);
  const buttons = document.querySelectorAll('#modeBtn, .topstrip-mode-btn');
  buttons.forEach(btn => {
    if (btn) btn.textContent = isLight ? '🌙 Dark Mode' : '☀ Light Mode';
  });
  localStorage.setItem('nestled_light', isLight ? '1' : '0');
}

function toggleMode() {
  applyMode(!document.body.classList.contains('light-mode'));
}

// Restore saved preference on load
(function () {
  const saved = localStorage.getItem('nestled_light');
  if (saved === '1') applyMode(true);
})();

/* ===== HEADER SCROLL EFFECT ===== */
const header = document.getElementById('mainHeader');
if (header) {
  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 40);
  }, { passive: true });
}

/* ===== MOBILE MENU ===== */
function toggleMobileMenu() {
  const menu = document.getElementById('mobileMenu');
  const burger = document.getElementById('hamburger');
  if (!menu) return;
  menu.classList.toggle('open');
  burger && burger.classList.toggle('open');
}

document.addEventListener('click', (e) => {
  const menu = document.getElementById('mobileMenu');
  const burger = document.getElementById('hamburger');
  if (
    menu &&
    menu.classList.contains('open') &&
    !menu.contains(e.target) &&
    burger &&
    !burger.contains(e.target)
  ) {
    menu.classList.remove('open');
    burger.classList.remove('open');
  }
});

/* ===== SCROLL REVEAL ===== */
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('revealed');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

/* ===== TOAST ===== */
function showToast(msg, duration = 3000) {
  const t = document.getElementById('globalToast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

/* ===== MENU TAB SWITCHING =====
   PHP renders all panels on page load.
   JS just shows/hides the correct panel — no data fetching needed.
========================================= */
function switchMenuTab(tab, btn) {
  // Update tab button states
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');

  // Show matching panel, hide others
  document.querySelectorAll('.menu-panel').forEach(panel => {
    const isActive = panel.dataset.panel === tab;
    panel.classList.toggle('active', isActive);
  });
}

/* ===== TESTIMONIAL SCROLLER ===== */
function scrollTestimonials(dir) {
  const track = document.getElementById('testimonialTrack');
  if (!track) return;
  track.scrollBy({ left: dir * 360, behavior: 'smooth' });
}

/* ===== MAP (Leaflet.js) ===== */
function initMap() {
  const mapEl = document.getElementById('map3d');
  if (!mapEl || typeof L === 'undefined') return;

  // University of San Carlos — Talamban Campus
  const USC_LAT = 10.3487;
  const USC_LNG = 123.9133;
  // Café slightly offset from USC
  const CAFE_LAT = 10.3492;
  const CAFE_LNG = 123.9128;

  const map = L.map('map3d', {
    center: [CAFE_LAT, CAFE_LNG],
    zoom: 16,
    zoomControl: true,
    scrollWheelZoom: false,
    attributionControl: true,
  });

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19,
  }).addTo(map);

  const cafeIcon = L.divIcon({
    html: `
      <div style="
        background: var(--accent, #C98A4E);
        width: 40px; height: 40px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        display: flex; align-items: center; justify-content: center;
      ">
        <span style="transform: rotate(45deg); font-size: 1.1rem; display: block; margin-left: 2px; margin-bottom: 2px;">☕</span>
      </div>
    `,
    className: '',
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -44],
  });

  const uscIcon = L.divIcon({
    html: `
      <div style="
        background: #4A90D9;
        width: 34px; height: 34px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        display: flex; align-items: center; justify-content: center;
      ">
        <span style="transform: rotate(45deg); font-size: 0.9rem; display: block;">🎓</span>
      </div>
    `,
    className: '',
    iconSize: [34, 34],
    iconAnchor: [17, 34],
    popupAnchor: [0, -38],
  });

  L.marker([CAFE_LAT, CAFE_LNG], { icon: cafeIcon })
    .addTo(map)
    .bindPopup(`
      <div style="font-family: 'DM Sans', sans-serif; padding: 4px;">
        <strong style="font-size: 1rem; color: #C98A4E;">☕ NestledBrew</strong><br>
        <span style="font-size: 0.82rem; opacity: 0.8;">LiveLoveLieSt., Cebu City</span><br>
        <span style="font-size: 0.78rem; color: #C98A4E; margin-top: 4px; display:block;">Open until 8:00 PM today</span>
      </div>
    `, { maxWidth: 200 })
    .openPopup();

  L.marker([USC_LAT, USC_LNG], { icon: uscIcon })
    .addTo(map)
    .bindPopup(`
      <div style="font-family: 'DM Sans', sans-serif; padding: 4px;">
        <strong style="font-size: 0.9rem;">🎓 University of San Carlos</strong><br>
        <span style="font-size: 0.78rem; opacity: 0.8;">Nasipit, Talamban, Cebu City</span>
      </div>
    `, { maxWidth: 200 });

  L.polyline([[USC_LAT, USC_LNG], [CAFE_LAT, CAFE_LNG]], {
    color: '#C98A4E',
    weight: 2,
    dashArray: '6 8',
    opacity: 0.7,
  }).addTo(map);

  L.marker([(USC_LAT + CAFE_LAT) / 2, (USC_LNG + CAFE_LNG) / 2], {
    icon: L.divIcon({
      html: `<div style="
        background: var(--surface, #3A2318);
        border: 1px solid rgba(201,138,78,0.4);
        border-radius: 6px;
        padding: 4px 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.72rem;
        font-weight: 500;
        color: #C98A4E;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
      ">~2 min walk</div>`,
      className: '',
      iconAnchor: [40, 10],
    }),
  }).addTo(map);

  window._mapInstance = map;
}

// Init map when DOM ready
if (document.getElementById('map3d')) {
  if (typeof L !== 'undefined') {
    initMap();
  } else {
    window.addEventListener('load', initMap);
  }
}