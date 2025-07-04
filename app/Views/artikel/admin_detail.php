<?= $this->include('template/admin_header'); ?>

<div class="container mt-4">
    <h2 class="mb-4"><?= $title; ?></h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-center mb-3"><?= esc($artikel['judul']); ?></h3>

            <!-- Kategori dan Status -->
            <p class="text-muted text-center mb-3">
                <strong>Kategori:</strong> <?= esc($artikel['nama_kategori']); ?> |
                <strong>Status:</strong> 
                <span class="<?= $artikel['status'] == 1 ? 'text-success' : 'text-secondary'; ?>">
                    <?= $artikel['status'] == 1 ? 'Publish' : 'Draft'; ?>
                </span>
            </p>

            <!-- Gambar -->
            <?php if ($artikel['gambar']): ?>
                <div class="text-center mb-4">
                    <img src="<?= base_url('/gambar/' . $artikel['gambar']); ?>" 
                         alt="Gambar Artikel" 
                         class="img-fluid rounded" 
                         style="max-width: 500px;">
                </div>
            <?php endif; ?>

            <!-- Isi Artikel -->
            <div class="artikel-isi" style="line-height: 1.8; font-size: 16px;">
                <?= nl2br(esc($artikel['isi'])); ?>
            </div>

            <!-- Tombol Navigasi -->
            <div class="mt-4 text-end">
                <a href="<?= base_url('/admin/artikel/edit/' . $artikel['id']); ?>" class="btn btn-warning">✏️ Edit</a>
                <a href="<?= base_url('/admin/artikel'); ?>" class="btn btn-secondary">← Kembali</a>
            </div>
        </div>
    </div>
</div>

<?= $this->include('template/admin_footer'); ?>
