<?php
namespace App\Controllers;
use App\Models\ArtikelModel;
use App\Models\KategoriModel;

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->getArtikelDenganKategori();
        return view('artikel/index', compact('artikel', 'title'));
    }

    public function admin_index()
    {
        $title = 'Daftar Artikel (Admin)';
        $model = new ArtikelModel();
        $q = $this->request->getVar('q') ?? '';
        $kategori_id = $this->request->getVar('kategori_id') ?? '';
        $page = $this->request->getVar('page') ?? 1;

        $builder = $model->table('artikel')
            ->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori')
            ->orderBy('artikel.id', 'DESC'); // tambah baris ini


        if ($q != '') {
            $builder->like('artikel.judul', $q);
        }

        if ($kategori_id != '') {
            $builder->where('artikel.id_kategori', $kategori_id);
        }

        $artikel = $builder->paginate(10, 'default', $page);
        $pager = $model->pager;

        $data = [
            'title' => $title,
            'q' => $q,
            'kategori_id' => $kategori_id,
            'artikel' => $artikel,
            'pager' => $pager,
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($data);
        } else {
            $kategoriModel = new \App\Models\KategoriModel();
            $data['kategori'] = $kategoriModel->findAll();
            return view('artikel/admin_index', $data);
        }
    }


    public function add()
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        if ($this->request->getPost()) {
            // Validasi input
            $validation = $this->validate([
                'judul' => 'required',
                'isi' => 'required',
                'status' => 'required|in_list[0,1]',
                'id_kategori' => 'required|is_natural_no_zero'
            ]);

            if (!$validation) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Siapkan slug dan data
            $slug = url_title($this->request->getPost('judul'), '-', true);

            $data = [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'status' => $this->request->getPost('status'),
                'slug' => $slug,
                'id_kategori' => $this->request->getPost('id_kategori')
            ];

            // Upload gambar jika ada
            $file = $this->request->getFile('gambar');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/gambar', $fileName);
                $data['gambar'] = $fileName;
            }

            // Simpan ke database
            if (!$model->insert($data)) {
                return redirect()->back()->withInput()->with('errors', $model->errors());
            }

            // Sukses
            return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil ditambahkan.');
        }

        // Jika GET, tampilkan form tambah
        $data['title'] = 'Tambah Artikel';
        $data['kategori'] = $kategoriModel->findAll();

        return view('artikel/form_add', $data);
    }


    public function admin_json()
    {
        $model = new ArtikelModel();
        $q = $this->request->getVar('q') ?? '';
        $kategori_id = $this->request->getVar('kategori_id') ?? '';

        $builder = $model->table('artikel')
            ->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori')
            ->orderBy('artikel.id', 'DESC');

        if ($q != '') {
            $builder->like('artikel.judul', $q);
        }

        if ($kategori_id != '') {
            $builder->where('artikel.id_kategori', $kategori_id);
        }

        $artikel = $builder->get()->getResultArray();

        return $this->response->setJSON(['artikel' => $artikel]);
    }

    public function edit($id)
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();
        $artikel = $model->find($id);

        if (!$artikel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Artikel dengan ID $id tidak ditemukan");
        }

        if ($this->request->getPost()) {
            $validation = $this->validate([
                'judul' => 'required',
                'isi' => 'required',
                'status' => 'required|in_list[0,1]',
                'id_kategori' => 'required|is_natural_no_zero'
            ]);

            if (!$validation) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'status' => $this->request->getPost('status'),
                'id_kategori' => $this->request->getPost('id_kategori'),
                'slug' => url_title($this->request->getPost('judul'), '-', true)
            ];

            $file = $this->request->getFile('gambar');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/gambar', $fileName);
                $data['gambar'] = $fileName;

                // Opsional: hapus gambar lama dari server jika perlu
                if (!empty($artikel['gambar']) && file_exists(ROOTPATH . 'public/gambar/' . $artikel['gambar'])) {
                    unlink(ROOTPATH . 'public/gambar/' . $artikel['gambar']);
                }
            }

            if (!$model->update($id, $data)) {
                return redirect()->back()->withInput()->with('errors', $model->errors());
            }

            return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil diperbarui.');
        }

        // Jika GET
        $data['artikel'] = $artikel;
        $data['kategori'] = $kategoriModel->findAll();
        $data['title'] = 'Edit Artikel';

        return view('artikel/form_edit', $data);
    }

    public function delete($id)
    {
        (new ArtikelModel())->delete($id);
        return redirect()->to('/admin/artikel');
    }

    public function view($slug)
    {
        $model = new ArtikelModel();
        $artikel = $model->join('kategori', 'kategori.id_kategori = artikel.id_kategori')
            ->where('slug', $slug)
            ->select('artikel.*, kategori.nama_kategori')
            ->first();
        if (empty($artikel)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Artikel tidak ditemukan.');
        }

        $data['artikel'] = $artikel;
        $data['title'] = $artikel['judul'];

        return view('artikel/detail', $data);
    }
    public function admin_detail($id)
{
    $model = new \App\Models\ArtikelModel();

    $artikel = $model->join('kategori', 'kategori.id_kategori = artikel.id_kategori')
                     ->select('artikel.*, kategori.nama_kategori')
                     ->where('artikel.id', $id)
                     ->first();

    if (!$artikel) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Artikel tidak ditemukan");
    }

    $data = [
        'title' => 'Detail Artikel (Admin)',
        'artikel' => $artikel
    ];

    return view('artikel/admin_detail', $data);
}

}
