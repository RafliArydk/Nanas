<div id="page-seller_notifications" class="page active">
  <div class="dash-layout">

    <!-- SIDEBAR (inline, seller style) -->
    <aside class="dash-sidebar seller-sidebar">
      <div class="sidebar-store-profile">
        <div class="sidebar-store-avatar">🏪</div>
        <div>
          <div class="sidebar-store-name"><?= e(current_user()['name'] ?? 'Penjual') ?></div>
          <div class="sidebar-store-status">Toko Aktif</div>
        </div>
      </div>
      <nav class="sidebar-nav">
        <div class="sidebar-group">
          <div class="sidebar-group-label">Menu Utama</div>
          <button class="sidebar-item" onclick="showPage('seller')"><span class="si">📊</span> Dashboard</button>
          <button class="sidebar-item" onclick="showPage('seller_products')"><span class="si">📦</span> Produk Saya</button>
          <button class="sidebar-item" onclick="showPage('seller_orders')"><span class="si">🛒</span> Pesanan Masuk</button>
          <button class="sidebar-item active"><span class="si">🔔</span> Notifikasi</button>
        </div>
      </nav>
      <div class="sidebar-footer">
        <button class="sidebar-item" onclick="doLogout()" style="color:#dc2626;width:100%">
          <span class="si">🚪</span> Keluar
        </button>
      </div>
    </aside>

    <div class="dash-content">
      <div class="dash-topbar">
        <div class="dash-topbar-left">
          <h2>🔔 Notifikasi</h2>
          <p>Pembaruan pesanan dan info penting untuk toko Anda</p>
        </div>
      </div>
      <div class="dash-body">