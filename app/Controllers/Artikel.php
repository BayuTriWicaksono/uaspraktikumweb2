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

        if ($this->request->getMethod() == 'post') {
            $validation = $this->validate([
                'judul' => 'required',
                'isi' => 'required',
                'status' => 'required|in_list[0,1]',
                'id_kategori' => 'required|is_natural_no_zero'
            ]);

            if ($validation) {
                $slug = url_title($this->request->getPost('judul'), '-', true);

                $data = [
                    'judul' => $this->request->getPost('judul'),
                    'isi' => $this->request->getPost('isi'),
                    'status' => $this->request->getPost('status'),
                    'slug' => $slug,
                    'id_kategori' => $this->request->getPost('id_kategori')
                ];

                // handle upload gambar
                if ($file = $this->request->getFile('gambar')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $fileName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/gambar', $fileName);
                        $data['gambar'] = $fileName;
                    }
                }

                $model->insert($data);
                return redirect()->to('/admin/artikel')->with('status', 'success');

            } else {
                // kirim error validasi ke view
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        // kalau GET request tampilkan form
        $data['title'] = 'Tambah Artikel';
        $data['kategori'] = $kategoriModel->findAll();
        return view('artikel/form_add', $data);
    }






    public function edit($id)
    {
        $model = new ArtikelModel();
        if (
            $this->request->getMethod() == 'post' && $this->validate([
                'judul' => 'required',
                'id_kategori' => 'required|integer'
            ])
        ) {
            $model->update($id, [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'id_kategori' => $this->request->getPost('id_kategori')
            ]);
            return redirect()->to('/admin/artikel');
        } else {
            $data['artikel'] = $model->find($id);
            $data['kategori'] = (new KategoriModel())->findAll();
            $data['title'] = "Edit Artikel";
            return view('artikel/form_edit', $data);
        }
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
}
