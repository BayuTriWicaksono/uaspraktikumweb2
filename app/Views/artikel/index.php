<?= $this->extend('layout/main') ?> 
<?= $this->section('content') ?>

<?php if ($artikel):
    foreach ($artikel as $row): ?>
        <article class="entry" style="margin-bottom: 25px;">
            <?php if ($row['gambar']): ?>
                <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="gambar"
                    style="width:60%; max-width:350px; height:auto; display:block; margin:0 auto 10px; border-radius: 4px;">
            <?php endif; ?>

            <h2 style="margin-bottom: 8px;">
                <a href="<?= base_url('/artikel/' . $row['slug']); ?>"
                    style="color:#3498db; text-decoration:none;"><?= $row['judul']; ?></a>
            </h2>

            <!-- Tambahkan kategori di sini -->
            <p style="margin-bottom: 10px; font-size: 14px; color: #555;">
                <b>Kategori:</b> <?= $row['nama_kategori']; ?>
            </p>

            <p style="line-height: 1.6;"><?= substr($row['isi'], 0, 200); ?>...</p>
            <a href="<?= base_url('/artikel/' . $row['slug']); ?>" class="btn"
                style="margin-top:10px; display:inline-block;">Baca Selengkapnya</a>
        </article>
        <hr class="divider" />
    <?php endforeach; else: ?>
    <article class="entry">
        <h2>Belum ada data.</h2>
    </article>
<?php endif; ?>

<?= $this->endSection() ?>
