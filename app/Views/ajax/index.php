<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h1>Data Artikel (AJAX)</h1>

<table class="table-data" id="artikelTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script src="<?= base_url('assets/js/jquery-3.7.1.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        function showLoadingMessage() {
            $('#artikelTable tbody').html('<tr><td colspan="4">Loading data...</td></tr>');
        }

        function loadData() {
            showLoadingMessage();
            $.ajax({
                url: "<?= base_url('ajax/getData') ?>",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    var tableBody = "";
                    data.forEach(function (row) {
                        tableBody += '<tr>';
                        tableBody += '<td>' + row.id + '</td>';
                        tableBody += '<td>' + row.judul + '</td>';
                        tableBody += '<td><span class="status">---</span></td>';
                        tableBody += '<td>';
                        tableBody += '<a href="<?= base_url('admin/artikel/edit/') ?>' + row.id + '" class="btn btn-primary">Edit</a> ';
                        tableBody += '<a href="#" class="btn btn-danger btn-delete" data-id="' + row.id + '">Hapus</a>';
                        tableBody += '</td>';
                        tableBody += '</tr>';
                    });
                    $('#artikelTable tbody').html(tableBody);
                }
            });
        }

        loadData();

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('Yakin ingin hapus artikel ini?')) {
                $.ajax({
                    url: "<?= base_url('ajax/delete/') ?>" + id,
                    method: "GET",
                    success: function (response) {
                        loadData();
                    },
                    error: function () {
                        alert('Gagal hapus artikel.');
                    }
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>