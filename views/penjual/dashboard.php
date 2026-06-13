<div id="page-seller" class="page active">
  <div class="dash-layout">

    <!-- SIDEBAR -->
    <aside class="dash-sidebar seller-sidebar">
      <div class="sidebar-store-profile">
        <div class="sidebar-store-avatar">🏪</div>
        <div>
          <div class="sidebar-store-name">Toko Buku Sari</div>
          <div class="sidebar-store-status">Toko Aktif</div>
        </div>
      </div>

      <nav class="sidebar-nav">
        <div class="sidebar-group">
          <div class="sidebar-group-label">Menu Utama</div>
          <button class="sidebar-item active">
            <span class="si">📊</span> Dashboard
          </button>
          <button class="sidebar-item" onclick="showToast('📦 Halaman Produk Saya')">
            <span class="si">📦</span> Produk Saya
            <span class="sidebar-badge">24</span>
          </button>
          <button class="sidebar-item" onclick="showToast('🛒 Pesanan Masuk')">
            <span class="si">🛒</span> Pesanan Masuk
            <span class="sidebar-badge warn">7</span>
          </button>
          <button class="sidebar-item" onclick="showToast('💬 Ulasan & Rating')">
            <span class="si">💬</span> Ulasan & Rating
          </button>
        </div>
        <div class="sidebar-group">
          <div class="sidebar-group-label">Keuangan</div>
          <button class="sidebar-item" onclick="showToast('💰 Laporan Penjualan')">
            <span class="si">💰</span> Laporan Penjualan
          </button>
          <button class="sidebar-item" onclick="showToast('🏦 Penarikan Dana')">
            <span class="si">🏦</span> Penarikan Dana
          </button>
          <button class="sidebar-item" onclick="showToast('🎟️ Promosi & Voucher')">
            <span class="si">🎟️</span> Promosi & Voucher
          </button>
        </div>
        <div class="sidebar-group">
          <div class="sidebar-group-label">Pengaturan</div>
          <button class="sidebar-item" onclick="showToast('⚙️ Pengaturan Toko')">
            <span class="si">⚙️</span> Pengaturan Toko
          </button>
        </div>
      </nav>

      <div class="sidebar-footer">
        <button class="sidebar-item" onclick="doLogout()" style="color:#dc2626;width:100%">
          <span class="si">🚪</span> Keluar
        </button>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="dash-content">

      <!-- Topbar -->
      <div class="dash-topbar">
        <div class="dash-topbar-left">
          <h2>Dashboard Penjual</h2>
          <p>Selamat datang kembali, <b><?= e(current_user()['name']) ?></b> 👋 — <?= date('l, d F Y') ?></p>
        </div>
        <div class="dash-topbar-right">
          <select class="dash-period-select">
            <option>Semua Waktu</option>
          </select>
          <button class="btn-dash-primary" onclick="showToast('📦 Form tambah produk dibuka!')">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Produk
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="dash-body">

        <!-- Alert strip -->
        <?php if (!empty($lowStockProducts)): ?>
        <div class="alert-strip">
          <span class="alert-strip-icon">⚠️</span>
          <div class="alert-strip-text"><b><?= count($lowStockProducts) ?> produk</b> hampir kehabisan stok. Segera lakukan restock agar tidak kehilangan pembeli.</div>
          <button class="alert-strip-action" onclick="showToast('📦 Menuju halaman stok...')">Lihat Produk →</button>
        </div>
        <?php endif; ?>

        <!-- Metrics -->
        <div class="metrics-grid">
          <!-- Revenue -->
          <div class="metric-card">
            <div class="metric-card-top">
              <div class="metric-icon-wrap" style="background:#f0fdf4">💰</div>
              <div class="metric-trend trend-up">-</div>
            </div>
            <div class="metric-val">Rp <?= number_format((float)$revenue, 0, ',', '.') ?></div>
            <div class="metric-label">Total Pendapatan</div>
            <div class="metric-sub">sepanjang waktu</div>
            <div class="metric-bar"><div class="metric-bar-fill" style="width:100%;background:linear-gradient(90deg,var(--accent),var(--accent-mid))"></div></div>
          </div>
          <!-- Orders -->
          <div class="metric-card">
            <div class="metric-card-top">
              <div class="metric-icon-wrap" style="background:#eff6ff">📦</div>
              <div class="metric-trend trend-up">-</div>
            </div>
            <div class="metric-val"><?= (int)$totalOrders ?></div>
            <div class="metric-label">Total Pesanan</div>
            <div class="metric-sub">sepanjang waktu</div>
            <div class="metric-bar"><div class="metric-bar-fill" style="width:100%;background:linear-gradient(90deg,#3b82f6,#60a5fa)"></div></div>
          </div>
          <!-- Products -->
          <div class="metric-card">
            <div class="metric-card-top">
              <div class="metric-icon-wrap" style="background:#fdf4ff">📚</div>
              <div class="metric-trend trend-neutral"><?= count($lowStockProducts) ?> ⚠️</div>
            </div>
            <div class="metric-val"><?= (int)$activeProducts ?></div>
            <div class="metric-label">Produk Aktif</div>
            <div class="metric-sub"><?= count($lowStockProducts) ?> stok hampir habis</div>
            <div class="metric-bar"><div class="metric-bar-fill" style="width:100%;background:linear-gradient(90deg,#a855f7,#c084fc)"></div></div>
          </div>
          <!-- Rating -->
          <div class="metric-card">
            <div class="metric-card-top">
              <div class="metric-icon-wrap" style="background:#fffbeb">⭐</div>
              <div class="metric-trend trend-up">-</div>
            </div>
            <div class="metric-val"><?= number_format((float)$ratingData['avg_rating'], 1) ?></div>
            <div class="metric-label">Rating Toko</div>
            <div class="metric-sub">dari <?= (int)$ratingData['total_reviews'] ?> ulasan pembeli</div>
            <div class="metric-bar"><div class="metric-bar-fill" style="width:<?= min(100, (float)$ratingData['avg_rating'] * 20) ?>%;background:linear-gradient(90deg,#f59e0b,#fbbf24)"></div></div>
          </div>
        </div>

        <!-- Charts row -->
        <div class="charts-row">
          <!-- Bar chart -->
          <div class="chart-card">
            <div class="chart-card-head">
              <div>
                <h4>Grafik Penjualan</h4>
                <p>Pendapatan 6 bulan terakhir</p>
              </div>
              <div class="chart-tabs">
                <button class="chart-tab active">Bulan</button>
                <button class="chart-tab" onclick="showToast('📈 Tampilan Mingguan')">Minggu</button>
              </div>
            </div>
            <div class="bar-chart-wrap">
              <div class="bar-chart-grid">
                <div class="bar-grid-line"><span class="bar-grid-label">5jt</span></div>
                <div class="bar-grid-line"><span class="bar-grid-label">3,75jt</span></div>
                <div class="bar-grid-line"><span class="bar-grid-label">2,5jt</span></div>
                <div class="bar-grid-line"><span class="bar-grid-label">1,25jt</span></div>
                <div class="bar-grid-line"><span class="bar-grid-label">0</span></div>
              </div>
              <div class="bar-chart-bars">
                <div class="bar-item">
                  <div class="bar-fill" style="height:42%;background:#e2e8f0">
                    <div class="bar-tooltip">Des: Rp 2,1jt</div>
                  </div>
                  <span class="bar-label">Des</span>
                </div>
                <div class="bar-item">
                  <div class="bar-fill" style="height:32%;background:#e2e8f0">
                    <div class="bar-tooltip">Jan: Rp 1,6jt</div>
                  </div>
                  <span class="bar-label">Jan</span>
                </div>
                <div class="bar-item">
                  <div class="bar-fill" style="height:54%;background:#e2e8f0">
                    <div class="bar-tooltip">Feb: Rp 2,7jt</div>
                  </div>
                  <span class="bar-label">Feb</span>
                </div>
                <div class="bar-item">
                  <div class="bar-fill" style="height:44%;background:#e2e8f0">
                    <div class="bar-tooltip">Mar: Rp 2,2jt</div>
                  </div>
                  <span class="bar-label">Mar</span>
                </div>
                <div class="bar-item">
                  <div class="bar-fill" style="height:66%;background:#bbf7d0">
                    <div class="bar-tooltip">Apr: Rp 3,3jt</div>
                  </div>
                  <span class="bar-label">Apr</span>
                </div>
                <div class="bar-item bar-current">
                  <div class="bar-fill" style="height:96%;background:linear-gradient(180deg, var(--accent-mid), var(--accent))">
                    <div class="bar-tooltip">Mei: Rp 4,8jt ✨</div>
                  </div>
                  <span class="bar-label" style="color:var(--accent);font-weight:700">Mei</span>
                </div>
              </div>
            </div>
            <div class="revenue-summary">
              <div class="rev-item">
                <div class="rev-dot" style="background:var(--accent)"></div>
                <div>
                  <div class="rev-label">Bulan Ini</div>
                  <div class="rev-val">Rp 4,8jt</div>
                </div>
              </div>
              <div class="rev-item">
                <div class="rev-dot" style="background:#e2e8f0"></div>
                <div>
                  <div class="rev-label">Rata-rata</div>
                  <div class="rev-val">Rp 2,8jt</div>
                </div>
              </div>
              <div class="rev-item" style="margin-left:auto">
                <div>
                  <div class="rev-label">Total 6 Bulan</div>
                  <div class="rev-val" style="color:var(--accent)">Rp 16,7jt</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Donut chart -->
          <div class="chart-card">
            <div class="chart-card-head">
              <div>
                <h4>Kategori Terlaris</h4>
                <p>Distribusi penjualan produk</p>
              </div>
            </div>
            <div class="donut-wrap">
              <div style="position:relative;display:inline-block">
                <svg width="130" height="130" viewBox="0 0 130 130">
                  <circle cx="65" cy="65" r="50" fill="none" stroke="#f1f5f9" stroke-width="18"/>
                  <!-- Pengembangan Diri 40% -->
                  <circle cx="65" cy="65" r="50" fill="none" stroke="var(--accent)" stroke-width="18"
                    stroke-dasharray="125.7 188.5" stroke-dashoffset="31.4" stroke-linecap="round"
                    transform="rotate(-90 65 65)"/>
                  <!-- Sastra 22% -->
                  <circle cx="65" cy="65" r="50" fill="none" stroke="#86efac" stroke-width="18"
                    stroke-dasharray="69.1 245.1" stroke-dashoffset="-94.2" stroke-linecap="round"
                    transform="rotate(-90 65 65)"/>
                  <!-- Keuangan 13% -->
                  <circle cx="65" cy="65" r="50" fill="none" stroke="#fbbf24" stroke-width="18"
                    stroke-dasharray="40.8 273.3" stroke-dashoffset="-163.4" stroke-linecap="round"
                    transform="rotate(-90 65 65)"/>
                  <!-- Lainnya 25% -->
                  <circle cx="65" cy="65" r="50" fill="none" stroke="#e2e8f0" stroke-width="18"
                    stroke-dasharray="78.5 235.6" stroke-dashoffset="-204.2" stroke-linecap="round"
                    transform="rotate(-90 65 65)"/>
                  <text x="65" y="60" text-anchor="middle" fill="#1e293b" font-size="18" font-weight="700" font-family="Playfair Display, serif">24</text>
                  <text x="65" y="75" text-anchor="middle" fill="#94a3b8" font-size="9.5" font-family="Plus Jakarta Sans, sans-serif">produk</text>
                </svg>
              </div>
              <div class="donut-legend-grid">
                <div class="donut-legend-item">
                  <div class="donut-legend-dot" style="background:var(--accent)"></div>
                  <div class="donut-legend-info">
                    <div class="dn">Pengembangan</div>
                    <div class="dv">40%</div>
                  </div>
                </div>
                <div class="donut-legend-item">
                  <div class="donut-legend-dot" style="background:#86efac"></div>
                  <div class="donut-legend-info">
                    <div class="dn">Sastra</div>
                    <div class="dv">22%</div>
                  </div>
                </div>
                <div class="donut-legend-item">
                  <div class="donut-legend-dot" style="background:#fbbf24"></div>
                  <div class="donut-legend-info">
                    <div class="dn">Keuangan</div>
                    <div class="dv">13%</div>
                  </div>
                </div>
                <div class="donut-legend-item">
                  <div class="donut-legend-dot" style="background:#e2e8f0"></div>
                  <div class="donut-legend-info">
                    <div class="dn">Lainnya</div>
                    <div class="dv">25%</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom row: Orders + Quick Actions -->
        <div class="bottom-row">
          <!-- Orders table -->
          <div class="orders-card">
            <div class="orders-card-head">
              <div>
                <h4>Pesanan Terbaru</h4>
              </div>
              <a href="#" onclick="showToast('📋 Semua pesanan')">Lihat semua →</a>
            </div>
            <table class="data-table">
              <thead>
                <tr>
                  <th>No. Pesanan</th>
                  <th>Pembeli</th>
                  <th>Buku</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($recentOrders)): ?>
                <tr>
                  <td colspan="6" class="text-center">Belum ada pesanan terbaru.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($recentOrders as $ro): ?>
                <tr>
                  <td><span class="td-order"><?= e($ro['invoice_number']) ?></span></td>
                  <td>
                    <div class="td-buyer">
                      <div class="td-buyer-avatar"><?= strtoupper(substr($ro['buyer_name'], 0, 2)) ?></div>
                      <span class="td-buyer-name"><?= e($ro['buyer_name']) ?></span>
                    </div>
                  </td>
                  <td><span class="td-book"><?= e($ro['product_names']) ?></span></td>
                  <td><span class="td-total">Rp <?= number_format((float)$ro['seller_total'], 0, ',', '.') ?></span></td>
                  <td>
                    <?php
                    $statusMap = [
                        'pending' => ['Menunggu', 's-pending'],
                        'paid' => ['Dibayar', 's-paid'],
                        'processing' => ['Diproses', 's-paid'],
                        'shipped' => ['Dikirim', 's-ship'],
                        'delivered' => ['Selesai', 's-done'],
                        'cancelled' => ['Batal', 's-cancel']
                    ];
                    $s = $statusMap[$ro['status']] ?? ['Unknown', 's-pending'];
                    ?>
                    <span class="status <?= $s[1] ?>">● <?= $s[0] ?></span>
                  </td>
                  <td><button class="action-btn" onclick="showToast('📋 Detail pesanan')">Detail</button></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Quick Actions -->
          <div style="display:flex; flex-direction:column; gap:16px;">
            <div class="quick-panel">
              <div class="quick-panel-head">
                <h4>Aksi Cepat</h4>
              </div>
              <div class="quick-actions-list">
                <button class="quick-action-item" onclick="showToast('📦 Form tambah produk')">
                  <div class="qa-icon" style="background:#f0fdf4">📦</div>
                  <div>
                    <div class="qa-title">Tambah Produk</div>
                    <div class="qa-desc">Upload buku baru ke toko</div>
                  </div>
                  <span class="qa-arrow">›</span>
                </button>
                <button class="quick-action-item" onclick="showToast('🎟️ Buat promo & voucher')">
                  <div class="qa-icon" style="background:#fffbeb">🎟️</div>
                  <div>
                    <div class="qa-title">Buat Promosi</div>
                    <div class="qa-desc">Diskon, flash sale, voucher</div>
                  </div>
                  <span class="qa-arrow">›</span>
                </button>
                <button class="quick-action-item" onclick="showToast('🏦 Tarik saldo toko')">
                  <div class="qa-icon" style="background:#eff6ff">🏦</div>
                  <div>
                    <div class="qa-title">Tarik Dana</div>
                    <div class="qa-desc">Saldo: Rp <?= number_format((float)$revenue, 0, ',', '.') ?></div>
                  </div>
                  <span class="qa-arrow">›</span>
                </button>
                <button class="quick-action-item" onclick="showToast('💬 Balas ulasan pembeli')">
                  <div class="qa-icon" style="background:#fdf4ff">💬</div>
                  <div>
                    <div class="qa-title">Balas Ulasan</div>
                    <div class="qa-desc">Lihat ulasan terbaru</div>
                  </div>
                  <span class="qa-arrow">›</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Low stock -->
        <?php if (!empty($lowStockProducts)): ?>
        <div class="lowstock-card">
          <div class="lowstock-card-head">
            <h4>⚠️ Stok Rendah</h4>
            <a href="#" style="font-size:12px;color:var(--accent);font-weight:600" onclick="showToast('📦 Halaman produk')">Kelola produk →</a>
          </div>
          <?php foreach ($lowStockProducts as $ls): ?>
          <div class="lowstock-row">
            <div class="ls-cover bc1"><?= strtoupper(substr($ls['name'], 0, 2)) ?></div>
            <div class="ls-info">
              <div class="ls-title"><?= e($ls['name']) ?></div>
              <div class="ls-cat">Rp <?= number_format((float)$ls['price'], 0, ',', '.') ?></div>
            </div>
            <div class="ls-stock-indicator">
              <div class="ls-stock-bar"><div class="ls-stock-bar-fill" style="width:<?= min(100, (int)$ls['stock'] * 10) ?>%;background:<?= $ls['stock'] <= 3 ? '#ef4444' : '#f59e0b' ?>"></div></div>
              <div class="ls-stock-num" <?= $ls['stock'] > 3 ? 'style="background:#fff7ed;color:#d97706"' : '' ?>><?= (int)$ls['stock'] ?></div>
              <button class="action-btn warn" onclick="showToast('📦 Form restock <?= e($ls['name']) ?>')">+ Restock</button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>