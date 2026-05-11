/* =========================================
   NestledBrew — script.js
   Shared across all pages
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
  // Using querySelectorAll ensures both buttons (top and main) update if they exist
  const buttons = document.querySelectorAll('#modeBtn, .topstrip-mode-btn');
  buttons.forEach(btn => {
    if (btn) btn.textContent = isLight ? '🌙 Dark Mode' : '☀ Light Mode';
  });
  localStorage.setItem('nestled_light', isLight ? '1' : '0');
}

// Restore saved preference
(function() {
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
  if (menu && menu.classList.contains('open') && !menu.contains(e.target) && !burger.contains(e.target)) {
    menu.classList.remove('open');
    burger && burger.classList.remove('open');
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

/* ===== MENU DATA & TAB SWITCHING ===== */
const menuData = {
  espresso: [
    { name: 'Signature Americano', desc: 'Bold double shot with hot water, smooth and rich with a dark chocolate finish.', price: '₱120', tag: 'Classic', icon: '☕' },
    { name: 'Caramel Macchiato', desc: 'Velvety steamed milk, vanilla syrup, espresso, and caramel drizzle.', price: '₱155', tag: 'Favourite', icon: '🍮' },
    { name: 'Cortado', desc: 'Equal parts espresso and warm milk. Clean, bold, balanced. Barista favourite.', price: '₱130', tag: 'Barista Pick', icon: '🥛' },
    { name: 'Flat White', desc: 'Micro-foam steamed milk over a double ristretto. Silky, strong, perfect.', price: '₱145', tag: 'Popular', icon: '✨' },
    { name: 'Espresso Tonic', desc: 'Double shot over sparkling tonic with a citrus twist. Refreshing and bold.', price: '₱160', tag: 'Signature', icon: '🍋' },
    { name: 'Dark Latte', desc: 'Our darkest roast, steamed whole milk, hint of muscovado. Rich and warming.', price: '₱150', tag: 'House Blend', icon: '🖤' },
  ],
  specialty: [
    { name: 'Ube Honey Latte', desc: 'Purple yam syrup, local honey, espresso, and creamy oat milk. A Filipino classic.', price: '₱175', tag: 'Local Special', icon: '💜' },
    { name: 'Brown Sugar Cold Brew', desc: 'Slow-steeped 18-hour cold brew with salted brown sugar and tiger crema.', price: '₱185', tag: 'Cold', icon: '🧊' },
    { name: 'Matcha Yuzu Latte', desc: 'Ceremonial grade matcha, yuzu citrus, oat milk, vanilla. Earthy and bright.', price: '₱175', tag: 'Trending', icon: '🍵' },
    { name: 'Rose Gold Latte', desc: 'Rose water, lychee syrup, steamed milk, single origin pour-over. Floral and delicate.', price: '₱190', tag: 'New', icon: '🌹' },
    { name: 'Dirty Chai', desc: 'Masala spice chai concentrate, steamed milk, espresso shot. Warming and complex.', price: '₱165', tag: 'Spiced', icon: '🌶' },
    { name: 'Citrus Cold Brew', desc: 'Tropical cold brew with orange peel, calamansi, and a pinch of sea salt.', price: '₱170', tag: 'Seasonal', icon: '🍊' },
  ],
  food: [
    { name: 'Avocado Toast', desc: 'Sourdough, smashed avo, heirloom tomatoes, chili flakes, poached egg.', price: '₱220', tag: 'All-Day', icon: '🥑' },
    { name: 'Croque Monsieur', desc: 'Toasted brioche, béchamel, gruyère, smoked ham. Classic café done right.', price: '₱245', tag: 'Chef Pick', icon: '🥪' },
    { name: 'Berry Yogurt Bowl', desc: 'Greek yogurt, seasonal berries, granola, drizzle of local wildflower honey.', price: '₱185', tag: 'Healthy', icon: '🍓' },
    { name: 'Walnut Banana Loaf', desc: 'Freshly baked daily. Caramelized banana, toasted walnuts, brown butter glaze.', price: '₱95', tag: 'Baked', icon: '🍌' },
    { name: 'Smoked Salmon Bagel', desc: 'Open-face everything bagel, cream cheese, capers, lemon, dill.', price: '₱265', tag: 'Weekend', icon: '🥯' },
    { name: 'Choco Croissant', desc: 'All-butter croissant, Belgian dark chocolate filling, powdered sugar.', price: '₱115', tag: 'Pastry', icon: '🥐' },
  ],
};

let currentTab = 'espresso';

function switchMenuTab(tab, btn) {
  currentTab = tab;
  // Update button states
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  else {
    const allBtns = document.querySelectorAll(`[data-tab="${tab}"]`);
    allBtns.forEach(b => b.classList.add('active'));
  }
  renderMenuGrid(tab);
}

function renderMenuGrid(tab) {
  const grid = document.getElementById('menuGrid');
  if (!grid) return;
  const items = menuData[tab] || [];
  grid.innerHTML = items.map((item, i) => `
    <div class="menu-card" style="animation-delay:${i * 0.06}s">
      <div class="menu-card-img">${item.icon}</div>
      <div class="menu-card-body">
        <div class="menu-card-name">${item.name}</div>
        <div class="menu-card-desc">${item.desc}</div>
        <div class="menu-card-footer">
          <div class="menu-card-price">${item.price}</div>
          <div class="menu-card-tag">${item.tag}</div>
        </div>
      </div>
    </div>
  `).join('');
}

// Initialize menu grid
if (document.getElementById('menuGrid')) {
  renderMenuGrid('espresso');
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

  // University of San Carlos coordinates (Talamban Campus)
  const USC_LAT = 10.3487;
  const USC_LNG = 123.9133;
  // Café slightly offset from USC
  const CAFE_LAT = 10.3492;
  const CAFE_LNG = 123.9128;

  const isLight = document.body.classList.contains('light-mode');

  const map = L.map('map3d', {
    center: [CAFE_LAT, CAFE_LNG],
    zoom: 16,
    zoomControl: true,
    scrollWheelZoom: false,
    attributionControl: true,
  });

  // Tile layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19,
  }).addTo(map);

  // Custom café marker
  const cafeIcon = L.divIcon({
    html: `
      <div style="
        background: var(--accent, #C98A4E);
        width: 40px;
        height: 40px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
      ">
        <span style="transform: rotate(45deg); font-size: 1.1rem; display: block; margin-left: 2px; margin-bottom: 2px;">☕</span>
      </div>
    `,
    className: '',
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -44],
  });

  // USC marker
  const uscIcon = L.divIcon({
    html: `
      <div style="
        background: #4A90D9;
        width: 34px;
        height: 34px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
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
        <span style="font-size: 0.82rem; opacity: 0.8;">123 Brew Street, Cebu City</span><br>
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

  // Draw a dashed line between USC and café
  L.polyline([[USC_LAT, USC_LNG], [CAFE_LAT, CAFE_LNG]], {
    color: '#C98A4E',
    weight: 2,
    dashArray: '6 8',
    opacity: 0.7,
  }).addTo(map);

  // Add a walking distance label
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
    })
  }).addTo(map);

  // Re-apply tile filter on mode change
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

// Update map tile filter on mode toggle
const _origToggleMode = window.toggleMode;
window.toggleMode = function() {
  _origToggleMode && _origToggleMode();
  applyMode(!document.body.classList.contains('light-mode'));
};

// Override to also handle map
(function() {
  const realApplyMode = applyMode;
  // Already defined above; we just need the tiles to update
  // The CSS handles this via .leaflet-tile-pane filter
})();