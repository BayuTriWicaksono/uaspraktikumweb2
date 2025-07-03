<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<?php if (session()->getFlashdata('errors')) : ?>
    <div style="color:red; margin-bottom:10px;">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<form action="<?= base_url('/admin/artikel/add') ?>" method="post" enctype="multipart/form-data">
    <p>
        <label>Judul</label>
        <input type="text" name="judul" required>
    </p>
    <p>
        <label>Isi</label>
        <textarea name="isi" cols="50" rows="10" required></textarea>
    </p>
    <p>
        <label>Status</label>
        <select name="status" required>
            <option value="1">Publish</option>
            <option value="0">Draft</option>
        </select>
    </p>
    <p>
        <label>Kategori</label>
        <select name="id_kategori" required>
            <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Upload Gambar</label>
        <input type="file" name="gambar">
    </p>
    <p>
        <input type="submit" value="Simpan" class="btn btn-primary">
    </p>
</form>

<?= $this->include('template/admin_footer'); ?>
