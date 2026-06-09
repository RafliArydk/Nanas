<!-- ══════════════════════════════════
  CART DRAWER
══════════════════════════════════ -->
<div class="overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-drawer-head">
    <h3>🛒 Keranjang Belanja</h3>
    <button class="close-btn" onclick="closeCart()">✕</button>
  </div>
  <div class="cart-items">
    <div class="cart-item">
      <div class="ci-cover bc1">Atomic Habits</div>
      <div class="ci-info">
        <div class="ci-title">Atomic Habits</div>
        <div class="ci-author">James Clear</div>
        <div class="ci-qty">
          <button class="qty-btn" onclick="changeQty(this,-1)">−</button>
          <span class="qty-val">1</span>
          <button class="qty-btn" onclick="changeQty(this,1)">+</button>
        </div>
      </div>
      <div class="ci-price">Rp 89.000</div>
    </div>
    <div class="cart-item">
      <div class="ci-cover bc2">Laskar Pelangi</div>
      <div class="ci-info">
        <div class="ci-title">Laskar Pelangi</div>
        <div class="ci-author">Andrea Hirata</div>
        <div class="ci-qty">
          <button class="qty-btn" onclick="changeQty(this,-1)">−</button>
          <span class="qty-val">2</span>
          <button class="qty-btn" onclick="changeQty(this,1)">+</button>
        </div>
      </div>
      <div class="ci-price">Rp 130.000</div>
    </div>
    <div class="cart-item">
      <div class="ci-cover bc3">Midnight Library</div>
      <div class="ci-info">
        <div class="ci-title">The Midnight Library</div>
        <div class="ci-author">Matt Haig</div>
        <div class="ci-qty">
          <button class="qty-btn" onclick="changeQty(this,-1)">−</button>
          <span class="qty-val">1</span>
          <button class="qty-btn" onclick="changeQty(this,1)">+</button>
        </div>
      </div>
      <div class="ci-price">Rp 76.000</div>
    </div>
  </div>
  <div class="cart-footer">
    <div class="cart-total-row"><span>Subtotal (4 item)</span><span>Rp 295.000</span></div>
    <div class="cart-total-row"><span>Ongkos Kirim</span><span>Rp 18.000</span></div>
    <div class="cart-total-row"><span>Diskon</span><span class="discount-val">−Rp 18.000</span></div>
    <div class="cart-total-row grand"><span>Total</span><span>Rp 295.000</span></div>
    <button class="btn-checkout" onclick="closeCart();showPage('checkout')">Lanjut ke Checkout →</button>
  </div>
</div>