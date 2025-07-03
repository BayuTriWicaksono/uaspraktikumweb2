<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<form action="" method="post">
    <p>
        <input type="text" name="judul" value="<?= $artikel['judul']; ?>" required>
    </p>
    <p>
        <textarea name="isi" cols="50" rows="10" required><?= $artikel['isi']; ?></textarea>
    </p>
    <p>
        <input type="submit" value="Kirim" class="btn btn-primary">
    </p>
</form>

<?= $this->include('template/admin_footer'); ?>
