<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Audit Log Aktivitas</h5>
                <div class="table-responsive">
                    <table class="display js-zero-conf-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>IP</th>
                                <th>Data Singkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $index => $row) : ?>
                                <tr>
                                    <td><?= esc((string) ($index + 1)) ?></td>
                                    <td><?= ! empty($row['created_at']) ? esc(date('d/m/Y H:i:s', strtotime((string) $row['created_at']))) : '-' ?></td>
                                    <td><?= esc((string) ($row['user_name'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($row['user_role'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($row['action'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($row['status_code'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($row['ip_address'] ?? '-')) ?></td>
                                    <td><?= esc((string) ($row['payload'] ?? '-')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

