<section class="rb-container">
    <div class="sec-head">
        <h2 class="sec-title">Kelola <span>Kategori</span></h2>
    </div>
    <section class="checkout-form" style="margin-bottom:22px">
        <form method="post">
            <input type="hidden" name="action" value="save_category">
            <div class="form-row">
                <div class="form-group"><label>Nama</label><input class="form-input" name="name" required></div>
                <div class="form-group"><label>Deskripsi</label><input class="form-input" name="description"></div>
            </div>
            <button class="btn-primary">Tambah Kategori</button>
        </form>
    </section>
    <table class="rb-table">
        <tr>
            <th>Nama</th>
            <th>Deskripsi</th>
        </tr>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= e($category['name']) ?></td>
                <td><?= e($category['description']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>