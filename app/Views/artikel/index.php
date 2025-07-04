<?= $this->extend('layout/main') ?> 
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Daftar Artikel</h2>

    <?php if ($artikel): ?>
        <?php foreach ($artikel as $row): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-body">

                    <?php if ($row['gambar']): ?>
                        <div class="text-center mb-3">
                            <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" 
                                 alt="gambar" 
                                 class="img-fluid rounded" 
                                 style="max-height: 250px; object-fit: cover;">
                        </div>
                    <?php endif; ?>

                    <h3 class="card-title">
                        <a href="<?= base_url('/artikel/' . $row['slug']); ?>" class="text-decoration-none text-primary">
                            <?= esc($row['judul']); ?>
                        </a>
                    </h3>

                    <!-- Kategori -->
                    <p class="text-muted mb-2">
                        <b>Kategori:</b> <?= esc($row['nama_kategori']); ?>
                    </p>

                    <!-- Ringkasan Isi -->
                    <p style="line-height: 1.6;">
                        <?= esc(substr(strip_tags($row['isi']), 0, 200)); ?>...
                    </p>

                    <!-- Tombol Baca Selengkapnya -->
                    <a href="<?= base_url('/artikel/' . $row['slug']); ?>" class="btn btn-outline-primary">
                        Baca Selengkapnya
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">Belum ada artikel yang tersedia.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
