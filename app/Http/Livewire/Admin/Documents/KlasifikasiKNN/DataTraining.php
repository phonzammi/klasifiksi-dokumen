<?php

namespace App\Http\Livewire\Admin\Documents\KlasifikasiKNN;

use App\Models\DataHasil;
use App\Models\DataTesting;
use App\Models\JenisDokumen;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\DataTraining as DataTrainingModel;
use Illuminate\Support\Facades\DB;

class DataTraining extends Component
{
    public $nama_dokumen, $jenis_dokumen_id, $dataTrainingModel;
    public $isEditing = false;
    public $openDataTrainingModal = false;
    public $openDeleteDataTrainingConfirmation = false;

    protected $validationAttributes = [
        'nama_dokumen' => 'Nama Dokumen',
        'jenis_dokumen_id' => 'Jenis Dokumen',
    ];

    protected $listeners = ['editDataTrainingModal', 'deleteDataTrainingConfirmation', 'closeModal' => 'closeDataTrainingModal'];

    public function updated($fields)
    {
        $this->nama_dokumen = Str::title($this->nama_dokumen);
        $this->validateOnly($fields, [
            'nama_dokumen' => ['required', Rule::unique('data_training')->ignore($this->dataTrainingModel)],
            'jenis_dokumen_id' => 'required|numeric',
        ]);
    }

    public function resetInput()
    {
        $this->nama_dokumen = "";
        $this->jenis_dokumen_id = "";
        $this->dataTrainingModel = "";
        $this->openDataTrainingModal = false;
        $this->isEditing = false;
        $this->emit('refreshDatatable');
    }

    public function createDataTrainingModal()
    {
        $this->openDataTrainingModal = true;
        $this->isEditing = false;
    }

    public function storeDataTraining()
    {
        $validatedData = $this->validate([
            'nama_dokumen' => 'required|unique:data_training,nama_dokumen',
            'jenis_dokumen_id' => 'required|numeric',
        ]);

        $data_training_baru = DataTrainingModel::create($validatedData);

        session()->flash('message', "Data Training Baru : '{$data_training_baru->nama_dokumen}' Berhasil Di Tambahkan !");
        $this->resetInput();
    }

    public function closeDataTrainingModal()
    {
        if ($this->isEditing || $this->openDeleteDataTrainingConfirmation) {
            $this->reset();
        }
        $this->openDataTrainingModal = false;
        $this->isEditing = false;
        $this->openDeleteDataTrainingConfirmation = false;
    }

    public function editDataTrainingModal(DataTrainingModel $data_training)
    {
        $this->openDataTrainingModal = true;
        $this->isEditing = true;

        $this->dataTrainingModel = $data_training;
        $this->nama_dokumen = $this->dataTrainingModel->nama_dokumen;
        $this->jenis_dokumen_id = $this->dataTrainingModel->jenis_dokumen_id;
    }

    public function updateDataTraining()
    {
        $validatedData = $this->validate([
            'nama_dokumen' => 'required|unique:data_training,nama_dokumen,' . $this->dataTrainingModel->id,
            'jenis_dokumen_id' => 'required|numeric',
        ]);

        $this->dataTrainingModel->update($validatedData);

        session()->flash('message', "Data Training : {$this->dataTrainingModel->nama_dokumen} Berhasil Diperbarui !");
        $this->emit('refreshDatatable');
        $this->reset();
    }

    public function deleteDataTrainingConfirmation(DataTrainingModel $data_training)
    {
        $this->dataTrainingModel = $data_training;
        $this->nama_dokumen = $this->dataTrainingModel->nama_dokumen;
        $this->openDeleteDataTrainingConfirmation = true;
    }

    public function deleteDataTraining()
    {
        $this->dataTrainingModel->delete();

        session()->flash('message', "Data Training : {$this->dataTrainingModel->nama_dokumen} Berhasil Dihapus !");
        $this->emit('refreshDatatable');
        $this->reset();
    }

    public function hapusSemuaData()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DataTrainingModel::truncate();
        DataTesting::truncate();
        DataHasil::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        session()->flash('message', "Semua Data Berhasil Dihapus !");
        $this->emit('refreshDatatable');
        $this->reset();
    }

    public function render()
    {
        $semua_jenis_dokumen = JenisDokumen::orderBy('jenis_dokumen')->get();
        return view('livewire.admin.documents.klasifikasi-k-n-n.data-training', compact('semua_jenis_dokumen'));
    }
}
