<section class="rb-container">
    <div class="sec-head"><h2 class="sec-title">Manage <span>Pesanan</span></h2></div>
    <table class="rb-table">
        <tr><th>Invoice</th><th>Total</th><th>Status</th><th>Update</th></tr>
        <?php foreach ($orders as $order): ?>
            <tr><td><?= e($order['invoice_number']) ?></td><td><?= rupiah($order['total']) ?></td><td><span class="rb-status"><?= e($order['status']) ?></span></td><td><form method="post" class="rb-actions"><input type="hidden" name="action" value="seller_order_status"><input type="hidden" name="order_id" value="<?= $order['id'] ?>"><select class="form-input" name="status" style="max-width:150px"><option>paid</option><option>processing</option><option>shipped</option><option>delivered</option></select><input class="form-input" name="receipt_number" placeholder="Nomor resi" style="max-width:180px"><button class="btn-primary">Simpan</button></form></td></tr>
        <?php endforeach; ?>
    </table>
</section>
