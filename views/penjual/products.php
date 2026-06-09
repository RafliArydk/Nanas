<section class="rb-container">
    <div class="sec-head"><h2 class="sec-title">CRUD <span>Produk</span></h2></div>
    <section class="checkout-form" style="margin-bottom:22px">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_product">
            <div class="form-row">
                <div class="form-group"><label>Nama</label><input class="form-input" name="name" required></div>
                <div class="form-group"><label>Kategori</label><select class="form-input" name="category_id"><?php foreach ($categories as $category): ?><option value="<?= $category['id'] ?>"><?= e($category['name']) ?></option><?php endforeach; ?></select></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Harga</label><input class="form-input" type="number" name="price" required></div>
                <div class="form-group"><label>Stock</label><input class="form-input" type="number" name="stock" required></div>
            </div>
            <div class="form-group"><label>Status</label><select class="form-input" name="status"><option>active</option><option>inactive</option></select></div>
            <div class="form-group"><label>Cover</label><input class="form-input" type="file" name="image" accept="image/*"></div>
            <div class="form-group"><label>Deskripsi</label><textarea class="form-input" name="description"></textarea></div>
            <button class="btn-primary">Tambah Buku</button>
        </form>
    </section>
    <table class="rb-table">
        <tr><th>Buku</th><th>Harga</th><th>Stock</th><th>Status</th><th></th></tr>
        <?php foreach ($products as $product): ?>
            <tr><td><?= e($product['name']) ?></td><td><?= rupiah($product['price']) ?></td><td><?= $product['stock'] ?></td><td><span class="rb-status"><?= e($product['status']) ?></span></td><td><form method="post"><input type="hidden" name="action" value="delete_product"><input type="hidden" name="id" value="<?= $product['id'] ?>"><button class="btn-secondary rb-danger">Delete</button></form></td></tr>
        <?php endforeach; ?>
    </table>
</section>
