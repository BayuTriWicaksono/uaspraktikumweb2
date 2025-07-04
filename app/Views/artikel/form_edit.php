<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<?php if (session()->getFlashdata('errors')): ?>
    <div style="color:red; margin-bottom:10px;">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<form action="<?= base_url('/admin/artikel/edit/' . $artikel['id']) ?>" method="post" enctype="multipart/form-data">

    <?= csrf_field() ?>
    
    <p>
        <label for="judul">Judul</label>
        <input type="text" name="judul" id="judul" value="<?= esc($artikel['judul']) ?>" required>
    </p>
    
    <p>
        <label>Isi</label>
        <textarea name="isi" cols="50" rows="10" required><?= esc($artikel['isi']) ?></textarea>
    </p>

    <p>
        <label>Status</label>
        <select name="status" required>
            <option value="1" <?= $artikel['status'] == 1 ? 'selected' : '' ?>>Publish</option>
            <option value="0" <?= $artikel['status'] == 0 ? 'selected' : '' ?>>Draft</option>
        </select>
    </p>

    <p>
        <label>Kategori</label>
        <select name="id_kategori" required>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id_kategori'] ?>" <?= $artikel['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                    <?= esc($k['nama_kategori']) ?>
                </option>
            <?php endforeach ?>
        </select>
    </p>

    <p>
        <label>Upload Gambar (opsional)</label>
        <input type="file" name="gambar">
    </p>

    <?php if (!empty($artikel['gambar'])): ?>
        <p>
            <strong>Gambar saat ini:</strong><br>
            <img src="<?= base_url('/gambar/' . $artikel['gambar']) ?>" alt="Gambar artikel" width="200">
        </p>
    <?php endif ?>

    <p>
        <input type="submit" value="Update" class="btn btn-primary">
    </p>
</form>

<?= $this->include('template/admin_footer'); ?>
