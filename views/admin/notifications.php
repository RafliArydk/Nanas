<div id="page-admin_notifications" class="page active">
  <div class="dash-layout">

    <!-- SIDEBAR (admin style) -->
    <aside class="dash-sidebar admin-sidebar">
      <div class="sidebar-store-profile">
        <div class="sidebar-store-avatar" style="background:linear-gradient(135deg,var(--accent),var(--accent-deep));font-size:18px">🖥️</div>
        <div>
          <div class="sidebar-store-name">Control Center</div>
          <div class="sidebar-store-status">Super Admin · v2.0</div>
        </div>
      </div>
      <nav class="sidebar-nav" style="flex:1">
        <div class="sidebar-group">
          <div class="sidebar-group-label">Overview</div>
          <button class="sidebar-item" onclick="showPage('admin')"><span class="si">📊</span> Dashboard</button>
        </div>
        <div class="sidebar-group">
          <div class="sidebar-group-label">Sistem</div>
          <button class="sidebar-item active"><span class="si">🔔</span> Notifikasi</button>
          <button class="sidebar-item" onclick="showToast('⚙️ Pengaturan Platform')"><span class="si">⚙️</span> Pengaturan</button>
        </div>
      </nav>
      <div class="sidebar-footer">
        <button class="sidebar-item" onclick="doLogout()" style="width:100%">
          <span class="si">🚪</span> Keluar
        </button>
      </div>
    </aside>

    <div class="dash-content" style="background:#f1f5f9">
      <div class="dash-topbar">
        <div class="dash-topbar-left">
          <h2>🔔 Notifikasi Admin</h2>
          <p>Pembaruan sistem dan aktivitas platform</p>
        </div>
      </div>
      <div class="dash-body">
        <div class="buyer-panel">
          <?php if (empty($notifications)): ?>
            <div class="buyer-empty">
              <div class="buyer-empty-icon">🔔</div>
              <p>Tidak ada notifikasi sistem saat ini.</p>
            </div>
          <?php else: ?>
            <div class="buyer-notif-list">
              <?php foreach ($notifications as $notif): ?>
                <div class="buyer-notif-item<?= !$notif['is_read'] ? ' unread' : '' ?>">
                  <div class="buyer-notif-dot"></div>
                  <div>
                    <p><?= e($notif['message']) ?></p>
                    <span><?= e(date('d M Y, H:i', strtotime($notif['created_at']))) ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>