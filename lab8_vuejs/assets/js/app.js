const { createApp } = Vue;
const apiUrl = 'http://localhost:8080/post';
 // sesuaikan jika endpoint CI4 kamu berbeda

createApp({
  data() {
    return {
      artikel: [],
      formData: {
        id: null,
        judul: '',
        isi: '',
        status: 0
      },
      showForm: false,
      formTitle: 'Tambah Data',
      statusOptions: [
        { text: 'Draft', value: 0 },
        { text: 'Publish', value: 1 }
      ]
    };
  },
  mounted() {
    this.loadData();
  },
  methods: {
    loadData() {
      axios.get(apiUrl)
        .then(res => this.artikel = res.data.artikel)
        .catch(err => console.log(err));
    },
    tambah() {
      this.showForm = true;
      this.formTitle = 'Tambah Data';
      this.formData = { id: null, judul: '', isi: '', status: 0 };
    },
    edit(data) {
      this.showForm = true;
      this.formTitle = 'Ubah Data';
      this.formData = { ...data };
    },
    hapus(index, id) {
      if (confirm('Yakin menghapus data?')) {
        axios.delete(`${apiUrl}/${id}`)
          .then(() => this.artikel.splice(index, 1))
          .catch(err => console.log(err));
      }
    },
    saveData() {
      if (this.formData.id) {
        axios.put(`${apiUrl}/${this.formData.id}`, this.formData)
          .then(() => this.loadData())
          .catch(err => console.log(err));
      } else {
        axios.post(apiUrl, this.formData)
          .then(() => this.loadData())
          .catch(err => console.log(err));
      }

      // Reset
      this.formData = { id: null, judul: '', isi: '', status: 0 };
      this.showForm = false;
    },
    statusText(status) {
      return status == 1 ? 'Publish' : 'Draft';
    }
  }
}).mount('#app');
