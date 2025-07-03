<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<article class="entry">
    <?php if ($artikel['gambar']): ?>
        <img src="<?= base_url('/gambar/' . $artikel['gambar']); ?>" alt="gambar"
            style="width:60%; max-width:400px; height:auto; display:block; margin:0 auto 20px; border-radius: 4px;">
    <?php endif; ?>

    <h2><?= $artikel['judul']; ?></h2>

    <!-- Kategori ditampilkan di sini -->
    <p style="margin-bottom: 15px; font-size: 14px; color: #555;">
        <b>Kategori:</b> <?= $artikel['nama_kategori']; ?>
    </p>

    <p style="line-height: 1.8; font-size: 15px;"><?= $artikel['isi']; ?></p>
</article>

<?= $this->endSection() ?>
