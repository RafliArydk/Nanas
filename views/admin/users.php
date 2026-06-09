<section class="rb-container">
    <div class="sec-head"><h2 class="sec-title">Kelola <span>User</span></h2></div>
    <table class="rb-table">
        <tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr>
        <?php foreach ($users as $userRow): ?>
            <tr>
                <td><?= e($userRow['name']) ?></td>
                <td><?= e($userRow['email']) ?></td>
                <td><?= e($userRow['role']) ?></td>
                <td><span class="rb-status"><?= e($userRow['status']) ?></span></td>
                <td class="rb-actions">
                    <?php if ($userRow['role'] === 'seller' && $userRow['status'] === 'pending'): ?>
                        <form method="post"><input type="hidden" name="action" value="approve_seller"><input type="hidden" name="seller_id" value="<?= $userRow['id'] ?>"><button class="btn-primary">Approve</button></form>
                    <?php endif; ?>
                    <?php if ($userRow['role'] !== 'admin'): ?>
                        <form method="post"><input type="hidden" name="action" value="ban_user"><input type="hidden" name="user_id" value="<?= $userRow['id'] ?>"><button class="btn-secondary rb-danger">Ban</button></form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
