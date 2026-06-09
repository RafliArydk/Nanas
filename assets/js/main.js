// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  STATE
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
let cartCount = 3;
let currentUser = null;   // { name, initials, role: 'buyer'|'seller'|'admin' }
let selectedRole = null;  // set during auth flow

// Demo users per role
const DEMO_USERS = {
  buyer:  { name: 'Sari Rahayu',      initials: 'SR' },
  seller: { name: 'Budi Santoso',     initials: 'BS' },
  admin:  { name: 'Admin RubbyBooks', initials: 'AR' }
};

// Role display config
const ROLE_CONFIG = {
  buyer: {
    chipLabel:    'Akun Saya',
    chipSublabel: 'Pembeli Â· RubbyBooks',
    chipClass:    '',             // default rose chip
    dotColor:     'var(--rose)',
    authHeader:   'default',
    badgeClass:   '',
    badgeText:    'ðŸ›’ Masuk sebagai Pembeli',
    tagline:      'Selamat datang kembali, pembaca!',
    navLinks:     ['home', 'catalog', 'tracking'],
    showCart:     true,
    showSearch:   true,
    dropdown: [
      { icon:'ðŸ ', label:'Beranda',         action:"_showPageDirect('home');closeUserDropdown()" },
      { icon:'ðŸ“–', label:'Katalog Buku',    action:"_showPageDirect('catalog');closeUserDropdown()" },
      { icon:'ðŸ“¦', label:'Lacak Pesanan',   action:"_showPageDirect('tracking');closeUserDropdown()" },
      { icon:'â¤ï¸', label:'Wishlist',        action:"showToast('â¤ï¸ Membuka Wishlist...');closeUserDropdown()" },
      { divider: true },
      { icon:'âš™ï¸', label:'Pengaturan',     action:"showToast('âš™ï¸ Pengaturan akun dibuka!');closeUserDropdown()" },
      { divider: true },
      { icon:'ðŸšª', label:'Keluar',          action:'doLogout()', danger: true }
    ]
  },
  seller: {
    chipLabel:    'Seller Dashboard',
    chipSublabel: 'Penjual Â· RubbyBooks',
    chipClass:    'role-seller',
    dotColor:     '#16a34a',
    authHeader:   'seller-theme',
    badgeClass:   'seller-badge',
    badgeText:    'ðŸ“¦ Masuk sebagai Penjual',
    tagline:      'Kelola toko buku Anda dengan mudah.',
    navLinks:     [],             // sembunyikan semua nav links â€” seller di dashboard
    showCart:     false,
    showSearch:   false,
    dropdown: [
      { icon:'ðŸ“Š', label:'Dashboard Penjual', action:"_showPageDirect('seller');closeUserDropdown()" },
      { icon:'ðŸ“¦', label:'Kelola Produk',    action:"_showPageDirect('seller');closeUserDropdown()" },
      { icon:'ðŸ“‹', label:'Pesanan Masuk',    action:"showToast('ðŸ“‹ Membuka pesanan masuk...');closeUserDropdown()" },
      { icon:'ðŸ“ˆ', label:'Laporan Penjualan',action:"showToast('ðŸ“ˆ Membuka laporan...');closeUserDropdown()" },
      { divider: true },
      { icon:'âš™ï¸', label:'Pengaturan Toko', action:"showToast('âš™ï¸ Pengaturan toko dibuka!');closeUserDropdown()" },
      { divider: true },
      { icon:'ðŸšª', label:'Keluar',           action:'doLogout()', danger: true }
    ]
  },
  admin: {
    chipLabel:    'Admin Panel',
    chipSublabel: 'Administrator Â· RubbyBooks',
    chipClass:    'role-admin',
    dotColor:     '#7c3aed',
    authHeader:   'admin-theme',
    badgeClass:   'admin-badge',
    badgeText:    'ðŸ” Akses Administrator',
    tagline:      'Masuk dengan kredensial admin.',
    navLinks:     [],             // admin hanya di panel
    showCart:     false,
    showSearch:   false,
    dropdown: [
      { icon:'ðŸ–¥ï¸', label:'Panel Admin',     action:"_showPageDirect('admin');closeUserDropdown()" },
      { icon:'ðŸ‘¥', label:'Manajemen User',  action:"showToast('ðŸ‘¥ Membuka manajemen user...');closeUserDropdown()" },
      { icon:'ðŸª', label:'Manajemen Toko',  action:"showToast('ðŸª Membuka manajemen toko...');closeUserDropdown()" },
      { icon:'ðŸ“Š', label:'Laporan Platform',action:"showToast('ðŸ“Š Membuka laporan platform...');closeUserDropdown()" },
      { icon:'ðŸ›¡ï¸', label:'Keamanan',        action:"showToast('ðŸ›¡ï¸ Membuka pengaturan keamanan...');closeUserDropdown()" },
      { divider: true },
      { icon:'ðŸšª', label:'Keluar',          action:'doLogout()', danger: true }
    ]
  }
};

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  ROLE-BASED ACCESS CONTROL (RBAC)
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

// Pages each role is ALLOWED to access
const PAGE_ACCESS = {
  guest:  ['home', 'catalog'],
  buyer:  ['home', 'catalog', 'checkout', 'tracking'],
  seller: ['seller'],
  admin:  ['admin']
};

// Human-readable page names for error messages
const PAGE_NAMES = {
  home:     'Beranda',
  catalog:  'Katalog Buku',
  checkout: 'Checkout',
  tracking: 'Lacak Pesanan',
  seller:   'Dashboard Penjual',
  admin:    'Panel Admin'
};

function canAccess(pageName) {
  const role = currentUser ? currentUser.role : 'guest';
  return (PAGE_ACCESS[role] || []).includes(pageName);
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  PAGE NAVIGATION  (with RBAC guard)
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function showPage(name) {
  if (!canAccess(name)) {
    handleAccessDenied(name);
    return;
  }
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  const page = document.getElementById('page-' + name);
  if (page) page.classList.add('active');
  updateNavActive(name);
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function handleAccessDenied(pageName) {
  const role = currentUser ? currentUser.role : 'guest';
  const pageLbl = PAGE_NAMES[pageName] || pageName;

  if (!currentUser) {
    showToast('ðŸ” Masuk dulu untuk mengakses ' + pageLbl);
    setTimeout(openAuth, 300);
    return;
  }

  if (role === 'seller') {
    showAccessGuard({
      icon: 'ðŸª',
      title: 'Akses Dibatasi',
      desc: 'Sebagai <b>Penjual</b>, Anda hanya dapat mengakses Dashboard Penjual. Keluar dan masuk sebagai Pembeli untuk mengakses halaman ini.',
      primaryLabel: 'Ke Dashboard Penjual',
      primaryAction: () => _showPageDirect('seller'),
      secondaryLabel: 'Keluar / Ganti Role',
      secondaryAction: doLogout
    });
  } else if (role === 'admin') {
    showAccessGuard({
      icon: 'ðŸ”',
      title: 'Akses Dibatasi',
      desc: 'Sebagai <b>Administrator</b>, Anda hanya dapat mengakses Panel Admin. Keluar untuk beralih role.',
      primaryLabel: 'Ke Panel Admin',
      primaryAction: () => _showPageDirect('admin'),
      secondaryLabel: 'Keluar',
      secondaryAction: doLogout
    });
  } else if (role === 'buyer' && (pageName === 'seller' || pageName === 'admin')) {
    showAccessGuard({
      icon: 'â›”',
      title: 'Halaman Tidak Tersedia',
      desc: 'Halaman <b>' + pageLbl + '</b> tidak dapat diakses oleh Pembeli. Silakan gunakan akun Penjual atau Admin.',
      primaryLabel: 'Kembali ke Beranda',
      primaryAction: () => _showPageDirect('home'),
      secondaryLabel: null
    });
  }
}

function showAccessGuard(cfg) {
  const existing = document.getElementById('access-guard-overlay');
  if (existing) existing.remove();

  const overlay = document.createElement('div');
  overlay.id = 'access-guard-overlay';
  overlay.className = 'page-access-guard';
  overlay.innerHTML = `
    <div class="guard-box">
      <div class="guard-icon">${cfg.icon}</div>
      <div class="guard-title">${cfg.title}</div>
      <div class="guard-desc">${cfg.desc}</div>
      <div class="guard-actions">
        <button class="btn-primary" onclick="document.getElementById('access-guard-overlay').remove();(${cfg.primaryAction.toString()})()">${cfg.primaryLabel}</button>
        ${cfg.secondaryLabel ? `<button class="btn-secondary" onclick="document.getElementById('access-guard-overlay').remove();(${cfg.secondaryAction.toString()})()">${cfg.secondaryLabel}</button>` : ''}
      </div>
    </div>`;
  document.body.appendChild(overlay);
  overlay.addEventListener('click', e => { if (e.target === overlay) overlay.remove(); });
}

function updateNavActive(pageName) {
  document.querySelectorAll('.nav-link').forEach(b => b.classList.remove('active'));
  const map = { home:'Beranda', catalog:'Katalog', checkout:'Checkout', tracking:'Lacak Pesanan',
                seller:'ðŸ“Š Dashboard', admin:'ðŸ–¥ï¸ Panel Admin' };
  const label = map[pageName];
  if (label) {
    document.querySelectorAll('.nav-link').forEach(b => {
      if (b.textContent.trim().includes(label.replace(/^[^\s]+ /,''))) b.classList.add('active');
    });
  }
}

function setActive(btn) {
  document.querySelectorAll('.nav-link').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

function setActiveByName(name) {
  document.querySelectorAll('.nav-link').forEach(b => {
    b.classList.toggle('active', b.textContent.trim() === name);
  });
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  AUTH MODAL
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function openAuth(hint) {
  document.getElementById('auth-step-role').style.display = 'block';
  document.getElementById('auth-step-form').style.display = 'none';
  selectedRole = null;
  document.getElementById('authModal').classList.add('open');
  document.body.style.overflow = 'hidden';
  if (hint === 'seller') {
    selectAuthRole('seller');
    setTimeout(() => switchTab('daftar'), 250);
  }
}

function closeAuth() {
  document.getElementById('authModal').classList.remove('open');
  document.body.style.overflow = '';
}

function backToRoleSelect() {
  document.getElementById('auth-step-role').style.display = 'block';
  document.getElementById('auth-step-form').style.display = 'none';
  selectedRole = null;
}

function selectAuthRole(role) {
  selectedRole = role;
  const cfg = ROLE_CONFIG[role];

  // Show form step
  document.getElementById('auth-step-role').style.display = 'none';
  document.getElementById('auth-step-form').style.display = 'block';

  // Apply theme to auth header
  const authTop = document.getElementById('auth-form-top');
  authTop.className = 'auth-top' + (cfg.authHeader !== 'default' ? ' ' + cfg.authHeader : '');

  // Badge
  const badge = document.getElementById('auth-role-badge');
  badge.textContent = cfg.badgeText;
  badge.className = 'auth-role-badge' + (cfg.badgeClass ? ' ' + cfg.badgeClass : '');

  // Tagline
  document.getElementById('auth-form-tagline').textContent = cfg.tagline;

  // Admin: hide Register tab; Seller: show Register with store field
  const tabDaftar = document.getElementById('tab-daftar');
  if (role === 'admin') {
    tabDaftar.style.display = 'none';
    switchTab('masuk');
  } else {
    tabDaftar.style.display = '';
    switchTab('masuk');
  }
  const sellerExtra = document.getElementById('seller-extra');
  if (sellerExtra) sellerExtra.style.display = role === 'seller' ? 'block' : 'none';
}

function switchTab(tab) {
  document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  document.getElementById('body-masuk').style.display = tab === 'masuk' ? 'block' : 'none';
  document.getElementById('body-daftar').style.display = tab === 'daftar' ? 'block' : 'none';
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  LOGIN / REGISTER
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function doLogin() {
  if (!selectedRole) return;
  loginSuccess({ ...DEMO_USERS[selectedRole], role: selectedRole });
}

function doRegister() {
  if (!selectedRole) return;
  const first = document.getElementById('reg-firstname').value.trim() || 'Pengguna';
  const last  = document.getElementById('reg-lastname').value.trim()  || 'Baru';
  loginSuccess({ name: first + ' ' + last, initials: (first[0] + (last[0] || '')).toUpperCase(), role: selectedRole });
}

function doSocialLogin() {
  if (!selectedRole) return;
  loginSuccess({ ...DEMO_USERS[selectedRole], role: selectedRole });
}

function loginSuccess(user) {
  currentUser = user;
  const cfg = ROLE_CONFIG[user.role];
  closeAuth();

  // â”€â”€ Apply body theme (swaps all accent CSS vars) â”€â”€
  document.body.className = document.body.className
    .replace(/\btheme-\w+/g, '').trim();
  if (user.role !== 'buyer') {
    document.body.classList.add('theme-' + user.role);
  }

  // â”€â”€ Update navbar â”€â”€
  document.getElementById('nav-guest').style.display = 'none';
  document.getElementById('nav-loggedin').style.display = 'block';

  // Avatar & labels
  document.getElementById('nav-avatar-initials').textContent = user.initials;
  document.getElementById('nav-username').textContent = cfg.chipLabel;
  document.getElementById('nav-userrole').textContent = cfg.chipSublabel;

  // Chip theme â€” now just use accent vars via body class; reset any legacy class
  const chip = document.getElementById('nav-user-chip');
  chip.className = 'nav-user-chip';

  // Role dot color (via CSS var now)
  const dot = document.getElementById('nav-role-dot');
  if (dot) dot.style.background = '';  // let CSS var handle it

  // Cart: show only for buyer
  const cartBtn = document.getElementById('nav-cart-btn');
  if (cartBtn) cartBtn.style.display = cfg.showCart ? 'flex' : 'none';

  // Search: hide for seller/admin
  const search = document.getElementById('nav-search');
  if (search) search.style.display = cfg.showSearch ? '' : 'none';

  // Nav links: switch to the correct nav link set
  renderNavLinks(user.role);

  // Build dynamic dropdown
  buildDropdown(cfg.dropdown);

  // â”€â”€ Apply role-specific UI restrictions â”€â”€
  applyRoleUI(user.role);

  // â”€â”€ Redirect â”€â”€
  const toastMsg = {
    buyer:  'âœ… Selamat datang, ' + user.name.split(' ')[0] + '!',
    seller: 'ðŸª Dashboard Penjual aktif',
    admin:  'ðŸ” Panel Admin aktif'
  };
  const targetPage = { buyer: 'home', seller: 'seller', admin: 'admin' };
  showToast(toastMsg[user.role]);
  _showPageDirect(targetPage[user.role]);
}

// Internal nav render â€” switches to the correct nav link group per role
function renderNavLinks(role) {
  const navCenter = document.getElementById('nav-center');
  if (!navCenter) return;

  // Always show nav-center; hide all groups first
  navCenter.style.display = 'flex';
  ['buyer', 'seller', 'admin'].forEach(r => {
    const el = document.getElementById('nav-links-' + r);
    if (el) el.style.display = 'none';
  });

  // Show the matching group (buyer for guest too)
  const group = (role === 'seller' || role === 'admin') ? role : 'buyer';
  const el = document.getElementById('nav-links-' + group);
  if (el) el.style.display = '';
}

// Apply role-specific element visibility (buyer-only CTAs etc.)
function applyRoleUI(role) {
  const buyerOnly = document.querySelectorAll('[data-role="buyer"]');
  buyerOnly.forEach(el => {
    el.style.display = (role === 'buyer') ? '' : 'none';
  });
}

// Direct page switch (skips RBAC guard â€” used internally after role is already validated)
function _showPageDirect(name) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  const page = document.getElementById('page-' + name);
  if (page) page.classList.add('active');
  updateNavActive(name);
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  DROPDOWN (dynamic per role)
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function buildDropdown(items) {
  const el = document.getElementById('userDropdown');
  el.innerHTML = items.map(item => {
    if (item.divider) return '<div class="dropdown-divider"></div>';
    return `<button class="dropdown-item${item.danger ? ' danger' : ''}" onclick="${item.action}"><span>${item.icon}</span> ${item.label}</button>`;
  }).join('');
}

function toggleUserDropdown() {
  document.getElementById('userDropdown').classList.toggle('open');
}

function closeUserDropdown() {
  document.getElementById('userDropdown').classList.remove('open');
}

document.addEventListener('click', e => {
  const wrap = document.querySelector('.nav-user-wrap');
  if (wrap && !wrap.contains(e.target)) closeUserDropdown();
});

// â”€â”€ Legacy goToDashboard (kept for compatibility) â”€â”€
function goToDashboard() {
  closeUserDropdown();
  if (!currentUser) return;
  const page = { buyer: 'home', seller: 'seller', admin: 'admin' };
  _showPageDirect(page[currentUser.role] || 'home');
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  LOGOUT
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function doLogout() {
  currentUser = null;
  selectedRole = null;
  // Remove body theme
  document.body.className = document.body.className
    .replace(/\btheme-\w+/g, '').trim();
  // Restore default navbar state
  document.getElementById('nav-guest').style.display = 'block';
  document.getElementById('nav-loggedin').style.display = 'none';
  // Reset chip class
  document.getElementById('nav-user-chip').className = 'nav-user-chip';
  // Restore buyer nav links
  renderNavLinks('buyer');
  const search = document.getElementById('nav-search');
  if (search) search.style.display = '';
  // Restore buyer-only elements
  document.querySelectorAll('[data-role="buyer"]').forEach(el => el.style.display = '');
  closeUserDropdown();
  _showPageDirect('home');
  showToast('ðŸ‘‹ Berhasil keluar. Sampai jumpa!');
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  CART
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function openCart() {
  if (currentUser && currentUser.role !== 'buyer') {
    showToast('â›” Keranjang belanja hanya tersedia untuk Pembeli');
    return;
  }
  document.getElementById('cartOverlay').classList.add('open');
  document.getElementById('cartDrawer').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeCart() {
  document.getElementById('cartOverlay').classList.remove('open');
  document.getElementById('cartDrawer').classList.remove('open');
  document.body.style.overflow = '';
}
function changeQty(btn, delta) {
  const el = btn.parentElement.querySelector('.qty-val');
  let q = Math.max(1, parseInt(el.textContent) + delta);
  el.textContent = q;
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  ADD TO CART
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function addToCart(e, name) {
  e.stopPropagation();
  // Only buyers (and guests who will be prompted to login) can add to cart
  if (currentUser && currentUser.role !== 'buyer') {
    showToast('â›” Fitur keranjang hanya tersedia untuk Pembeli');
    return;
  }
  if (!currentUser) {
    showToast('ðŸ” Masuk sebagai Pembeli untuk menambahkan ke keranjang');
    setTimeout(openAuth, 400);
    return;
  }
  cartCount++;
  document.getElementById('cart-badge').textContent = cartCount;
  const cb = document.getElementById('cart-badge-auth');
  if (cb) cb.textContent = cartCount;
  const btn = e.currentTarget;
  btn.style.background = 'var(--rose)';
  btn.style.color = '#fff';
  btn.textContent = 'âœ“';
  setTimeout(() => { btn.style.background=''; btn.style.color=''; btn.textContent='+ Keranjang'; }, 1500);
  showToast('ðŸ›’ ' + name + ' ditambahkan ke keranjang!');
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  WISHLIST
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function toggleWish(btn) {
  const active = btn.textContent === 'â™¥';
  btn.textContent = active ? 'â™¡' : 'â™¥';
  btn.style.color = active ? '' : 'var(--rose)';
  if (!active) showToast('â¤ï¸ Ditambahkan ke Wishlist!');
}
function toggleWishFeatured(btn) {
  const active = btn.textContent.includes('â™¥');
  btn.innerHTML = active ? 'â™¡ Wishlist' : 'â™¥ Tersimpan';
  if (!active) showToast('â¤ï¸ Ditambahkan ke Wishlist!');
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  FILTERS
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
function filterCat(el) {
  el.closest('.cat-strip').querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  showToast('ðŸ” Menampilkan: ' + el.textContent.trim());
}
function selectPay(el) {
  (el.closest('.pay-grid') || el.parentElement).querySelectorAll('.pay-opt').forEach(o => o.classList.remove('active'));
  el.classList.add('active');
}
function updatePrice(input) {
  document.getElementById('price-max').textContent = 'Rp ' + parseInt(input.value).toLocaleString('id-ID');
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  TOAST
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
let toastTimer;
function showToast(msg) {
  const t = document.getElementById('toast');
  const icon = msg.match(/^([\u{1F300}-\u{1FFFF}]|[\u2600-\u27FF]|âœ…|âŒ|âš ï¸|ðŸ”|ðŸ”|ðŸ›’|ðŸ“‹|ðŸ‘‹|ðŸª)/u)?.[0] || 'âœ…';
  t.querySelector('.toast-icon').textContent = icon;
  document.getElementById('toast-msg').textContent = msg.replace(/^[\u{1F300}-\u{1FFFF}]|^[\u2600-\u27FF]|^âœ…|^âŒ|^âš ï¸|^ðŸ”|^ðŸ”|^ðŸ›’|^ðŸ“‹|^ðŸ‘‹|^ðŸª/u, '').trim();
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 3200);
}

// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
//  KEYBOARD
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeCart(); closeAuth(); closeUserDropdown(); }
});
