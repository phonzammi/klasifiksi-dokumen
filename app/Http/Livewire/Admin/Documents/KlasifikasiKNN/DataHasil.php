<?php

namespace App\Http\Livewire\Admin\Documents\KlasifikasiKNN;

use App\Models\DataTesting;
use App\Models\DataTraining;
use CosineSimilarity as CosSim;
use Livewire\Component;
use StopWords\StopWords;
use Sastrawi\Stemmer\StemmerFactory;
use App\Models\DataHasil as DataHasilModel;
use App\Models\JenisDokumen;

class DataHasil extends Component
{
    public $semua_data_training = NULL;
    public $semua_data_testing = NULL;

    public function klasifikasiKnn()
    {
        $this->semua_data_training = DataTraining::orderBy('jenis_dokumen_id')->with('jenis_dokumen')->get();
        // dd($this->semua_data_training->toArray());
        $this->semua_data_testing = DataTesting::all();
        if (!$this->semua_data_training->count() > 0) {
            session()->flash('error', "Data Training Tidak Tersedia");
            $this->reset();
        }

        if (!$this->semua_data_testing->count() > 0) {
            session()->flash('error', "Data Testing Tidak Tersedia");
            $this->reset();
        }

        if ($this->semua_data_training->count() > 1 && $this->semua_data_testing->count() > 0) {
            // A. Preprocessing :
            // ----1. strtolower (mengubah menjadi huruf kecil semua)
            foreach ($this->semua_data_training as $data_training) {
                $data_training->nama_dokumen = strtolower($data_training->nama_dokumen);
            }

            foreach ($this->semua_data_testing as $data_testing) {
                $data_testing->nama_dokumen = strtolower($data_testing->nama_dokumen);

                // ---2. Remove Stop Words (Hapus kata sambung):
                $stopwords = new StopWords('id');

                foreach ($this->semua_data_training as $data_training) {
                    $data_training->nama_dokumen = $stopwords->clean($data_training->nama_dokumen);
                }

                $data_testing->nama_dokumen = $stopwords->clean($data_testing->nama_dokumen);

                // ---3. Stemming (konversi ke kata dasar):

                $stemmerFactory = new StemmerFactory();
                $stemmer  = $stemmerFactory->createStemmer();

                foreach ($this->semua_data_training as $data_training) {
                    $data_training->nama_dokumen = $stemmer->stem($data_training->nama_dokumen);
                }

                $data_testing->nama_dokumen = $stemmer->stem($data_testing->nama_dokumen);

                // ---4. Tokenizing (pisah kata)
                $terms = array();
                foreach ($this->semua_data_training as $data_training) {
                    $terms = array_unique(array_merge($terms, explode(" ", $data_training->nama_dokumen)), SORT_REGULAR);
                }
                $terms = array_unique(array_merge($terms, explode(" ", $data_testing->nama_dokumen)), SORT_REGULAR);


                // B. Preprocessing Nama Dokumen
                // ---1. Term Weighting TF-IDF
                // ----a. Hitung TF
                $semua_tf_d = array();
                foreach ($terms as $term) {
                    $no = 1;
                    foreach ($this->semua_data_training as $data_training) {
                        $semua_tf_d[$no][] = substr_count($data_training->nama_dokumen, $term);
                        $data_training->tf = substr_count($data_training->nama_dokumen, $term);
                        $no++;
                    }
                    $semua_tf_d[$no][] = substr_count($data_testing->nama_dokumen, $term);
                }

                // ----b. Hitung DF
                $data_freq = array();
                for ($i = 0; $i < count($terms); $i++) {
                    $data_freq[] = array_sum(array_column($semua_tf_d, $i));
                }


                // ----c. Hitung IDF
                $idf = array();
                foreach ($data_freq as $df) {
                    $n_per_df = count($semua_tf_d) / $df;
                    $idf[] = log($n_per_df, 10);
                }

                // ----d. Finalize Bobot (Wdt)
                $w_dt = array();
                $no = 0;
                foreach ($semua_tf_d as $tf_of_d) {
                    foreach ($tf_of_d as $index => $tf_d) {
                        $w_dt[$no][] = $tf_d * $idf[$index];
                    }
                    $no++;
                }

                // C. Hitung Kemiripan Vektor Nama Dokumen (Cosine Similarity) dan Klasifikasi KNN
                for ($i = 0; $i < count($w_dt) - 1; $i++) {
                    $a = $w_dt[$i];
                    $b = end($w_dt);
                    $this->semua_data_training[$i]->cosine_similarity = CosSim::calc($b, $a);
                }

                $nama_dokumen = DataTesting::find($data_testing->id)->nama_dokumen;
                $jenis_dokumen_id = JenisDokumen::find($this->semua_data_training->sortByDesc('cosine_similarity')->first()->jenis_dokumen_id)->id;
                $nilai_kemiripan = $this->semua_data_training->sortByDesc('cosine_similarity')->first()->cosine_similarity;
                $data_training_id = $this->semua_data_training->sortByDesc('cosine_similarity')->first()->id;

                DataHasilModel::create([
                    'nama_dokumen' => $nama_dokumen,
                    'jenis_dokumen_id' => $jenis_dokumen_id,
                    'nilai_kemiripan' => $nilai_kemiripan,
                    'data_training_id' => $data_training_id
                ]);
            }
            session()->flash('message', "Jenis Dokumen :  Berhasil Di Klasifikasi !");
            $this->emit('refreshDatatable');
            $this->reset();
        }
    }
    public function render()
    {
        return view('livewire.admin.documents.klasifikasi-k-n-n.data-hasil');
    }
}
