<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<a href="/admin/artikel/add" class="btn btn-success mb-3">+ Tambah Artikel</a>

<div class="row mb-3">
    <div class="col-md-6">
        <form id="search-form" class="form-inline">
            <input type="text" name="q" id="search-box" value="<?= $q; ?>" placeholder="Cari judul artikel"
                class="form-control mr-2">
            <select name="kategori_id" id="category-filter" class="form-control mr-2">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori']; ?>" <?= ($kategori_id == $k['id_kategori']) ? 'selected' : ''; ?>>
                        <?= $k['nama_kategori']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Cari" class="btn btn-primary">
        </form>
    </div>
</div>

<div id="article-container"></div>
<div id="pagination-container"></div>
<div id="loading-message" style="display:none; margin:10px 0; font-style:italic;">Memuat data...</div>

<script src="<?= base_url('assets/js/jquery-3.7.1.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        const articleContainer = $('#article-container');
        const paginationContainer = $('#pagination-container');
        const searchForm = $('#search-form');
        const searchBox = $('#search-box');
        const categoryFilter = $('#category-filter');
        const loadingMessage = $('#loading-message');

        function fetchData(q = '', kategori_id = '') {
            loadingMessage.show();
            $.ajax({
                url: '/admin/artikel/json',
                type: 'GET',
                dataType: 'json',
                data: { q, kategori_id },
                success: function (data) {
                    renderArticles(data.artikel);
                    loadingMessage.hide();
                },
                error: function () {
                    articleContainer.html('<div class="alert alert-danger">Gagal memuat data.</div>');
                    loadingMessage.hide();
                }
            });
        }

        function renderArticles(articles) {
            let html = '<table class="table table-data">';
            html += '<thead><tr><th>ID</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead><tbody>';

            if (articles.length > 0) {
                articles.forEach(article => {
                    html += `<tr>
                    <td>${article.id}</td>
                    <td><b>${article.judul}</b><p><small>${article.isi.substring(0, 50)}...</small></p></td>
                    <td>${article.nama_kategori}</td>
                    <td>${article.status}</td>
                    <td>
                    <a class="btn btn-sm btn-secondary" href="/admin/artikel/detail/${article.id}">Detail</a>
                    <a class="btn btn-sm btn-info" href="/admin/artikel/edit/${article.id}">Ubah</a>
                    <a class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data?');" href="/admin/artikel/delete/${article.id}">Hapus</a>
                    </td>

                </tr>`;
                });
            } else {
                html += '<tr><td colspan="5">Tidak ada data.</td></tr>';
            }
            html += '</tbody></table>';
            articleContainer.html(html);
        }

        searchForm.on('submit', function (e) {
            e.preventDefault();
            const q = searchBox.val();
            const kategori_id = categoryFilter.val();
            fetchData(q, kategori_id);
        });

        categoryFilter.on('change', function () {
            searchForm.trigger('submit');
        });

        fetchData();
    });

</script>

<?= $this->include('template/admin_footer'); ?>x