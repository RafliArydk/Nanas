<div class="modal-overlay" id="authModal" onclick="if(event.target===this)closeAuth()">
  <div class="auth-modal" style="max-width:480px">

    <!-- STEP 1: Pilih Role -->
    <div id="auth-step-role">
      <div class="auth-top">
        <button onclick="closeAuth()" style="position:absolute;top:16px;right:20px;background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:50%;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2">✕</button>
        <div class="auth-logo">📚 RubbyBooks</div>
        <div class="auth-tagline">Masuk sebagai siapa?</div>
      </div>
      <div style="padding:28px 32px 32px">
        <div style="font-size:13px;font-weight:700;color:var(--ink-mid);margin-bottom:16px;text-align:center">Pilih peran akun Anda untuk melanjutkan</div>
        <div style="display:flex;flex-direction:column;gap:12px">
          <button class="role-select-card" onclick="selectAuthRole('buyer')">
            <div class="rsc-icon" style="background:linear-gradient(135deg,#fff0f7,var(--rose-pale));color:var(--rose)">🛒</div>
            <div class="rsc-body">
              <div class="rsc-title">Pembeli</div>
              <div class="rsc-desc">Jelajahi & beli buku favorit Anda</div>
            </div>
            <div class="rsc-arrow">›</div>
          </button>
          <button class="role-select-card" onclick="selectAuthRole('seller')">
            <div class="rsc-icon" style="background:linear-gradient(135deg,#f0fff4,#dcfce7);color:#15803d">📦</div>
            <div class="rsc-body">
              <div class="rsc-title">Penjual</div>
              <div class="rsc-desc">Kelola toko & jual koleksi buku Anda</div>
            </div>
            <div class="rsc-arrow">›</div>
          </button>
          <button class="role-select-card" onclick="selectAuthRole('admin')">
            <div class="rsc-icon" style="background:linear-gradient(135deg,#1e1b2e,#2d2547);color:#a78bfa">🔐</div>
            <div class="rsc-body">
              <div class="rsc-title">Administrator</div>
              <div class="rsc-desc">Akses panel kontrol platform</div>
            </div>
            <div class="rsc-arrow">›</div>
          </button>
        </div>
        <div style="margin-top:20px;text-align:center;font-size:12px;color:var(--ink-muted)">
          Belum punya akun? <button onclick="selectAuthRole('buyer');setTimeout(()=>switchTab('daftar'),300)" style="background:none;border:none;color:var(--rose);font-weight:700;cursor:pointer;font-size:12px">Daftar Gratis</button>
        </div>
      </div>
    </div>

    <!-- STEP 2: Form Login/Register (tersembunyi awalnya) -->
    <div id="auth-step-form" style="display:none">
      <div class="auth-top" id="auth-form-top">
        <button onclick="backToRoleSelect()" style="position:absolute;top:16px;left:16px;background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:50%;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2">‹</button>
        <button onclick="closeAuth()" style="position:absolute;top:16px;right:20px;background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:50%;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2">✕</button>
        <div class="auth-role-badge" id="auth-role-badge">🛒 Masuk sebagai Pembeli</div>
        <div class="auth-logo">📚 RubbyBooks</div>
        <div class="auth-tagline" id="auth-form-tagline">Selamat datang kembali!</div>
      </div>
      <div class="auth-tabs">
        <button class="auth-tab active" id="tab-masuk" onclick="switchTab('masuk')">Masuk</button>
        <button class="auth-tab" id="tab-daftar" onclick="switchTab('daftar')" id="tab-daftar-btn">Daftar Akun</button>
      </div>
      <!-- LOGIN FORM -->
      <div id="body-masuk" class="auth-body">
        <div class="form-group"><label>Email</label><input class="form-input" type="email" placeholder="kamu@email.com" id="login-email"></div>
        <div class="form-group"><label>Password</label><input class="form-input" type="password" placeholder="••••••••" id="login-pass"></div>
        <div class="remember-row">
          <label><input type="checkbox"> Ingat saya</label>
          <a href="#">Lupa password?</a>
        </div>
        <button class="btn-submit" onclick="doLogin()">Masuk ke RubbyBooks</button>
        <div class="divider-text">atau masuk dengan</div>
        <div style="display:flex;gap:10px">
          <button style="flex:1;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius-sm);background:var(--white);font-size:13px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:7px;transition:var(--transition)" onmouseover="this.style.background='var(--rose-blush)'" onmouseout="this.style.background='var(--white)'" onclick="doSocialLogin()">🌐 Google</button>
          <button style="flex:1;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius-sm);background:var(--white);font-size:13px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:7px;transition:var(--transition)" onmouseover="this.style.background='var(--rose-blush)'" onmouseout="this.style.background='var(--white)'" onclick="doSocialLogin()">📱 WhatsApp</button>
        </div>
      </div>
      <!-- REGISTER FORM -->
      <div id="body-daftar" class="auth-body" style="display:none">
        <div class="form-row">
          <div class="form-group"><label>Nama Depan</label><input class="form-input" placeholder="Sari" id="reg-firstname"></div>
          <div class="form-group"><label>Nama Belakang</label><input class="form-input" placeholder="Rahayu" id="reg-lastname"></div>
        </div>
        <div class="form-group"><label>Email</label><input class="form-input" type="email" placeholder="kamu@email.com" id="reg-email"></div>
        <div class="form-group"><label>Password</label><input class="form-input" type="password" placeholder="Min. 8 karakter, huruf & angka" id="reg-pass"></div>
        <div id="seller-extra" style="display:none">
          <div class="form-group"><label>Nama Toko</label><input class="form-input" placeholder="contoh: Toko Buku Sari"></div>
        </div>
        <button class="btn-submit" onclick="doRegister()">Buat Akun Gratis</button>
      </div>
    </div>

  </div>
</div>