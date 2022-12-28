<?php

namespace App\Http\Livewire\Admin\Documents\KlasifikasiKNN;

use App\Models\DataHasil;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\DataTesting as DataTestingModel;
use Illuminate\Support\Facades\DB;

class DataTesting extends Component
{
    public $nama_dokumen, $dataTestingModel;
    public $isEditing = false;
    public $openDataTestingModal = false;
    public $openDeleteDataTestingConfirmation = false;

    protected $validationAttributes = [
        'nama_dokumen' => 'Nama Dokumen',
    ];

    protected $listeners = ['editDataTestingModal', 'deleteDataTestingConfirmation', 'closeModal' => 'closeDataTestingModal'];

    public function updated($fields)
    {
        $this->nama_dokumen = Str::title($this->nama_dokumen);
        $this->validateOnly($fields, [
            'nama_dokumen' => ['required', Rule::unique('data_testing')->ignore($this->dataTestingModel)],
        ]);
    }

    public function resetInput()
    {
        $this->nama_dokumen = "";
        $this->dataTestingModel = "";
        $this->openDataTestingModal = false;
        $this->isEditing = false;
        $this->emit('refreshDatatable');
    }

    public function createDataTestingModal()
    {
        $this->openDataTestingModal = true;
        $this->isEditing = false;
    }

    public function storeDataTesting()
    {
        $validatedData = $this->validate([
            'nama_dokumen' => 'required|unique:data_testing,nama_dokumen',
        ]);

        $data_testing_baru = DataTestingModel::create($validatedData);

        session()->flash('message', "Data Testing Baru : '{$data_testing_baru->nama_dokumen}' Berhasil Di Tambahkan !");
        $this->resetInput();
    }

    public function closeDataTestingModal()
    {
        if ($this->isEditing || $this->openDeleteDataTestingConfirmation) {
            $this->reset();
        }
        $this->openDataTestingModal = false;
        $this->isEditing = false;
        $this->openDeleteDataTestingConfirmation = false;
    }

    public function editDataTestingModal(DataTestingModel $data_testing)
    {
        $this->openDataTestingModal = true;
        $this->isEditing = true;

        $this->dataTestingModel = $data_testing;
        $this->nama_dokumen = $this->dataTestingModel->nama_dokumen;
    }

    public function updateDataTesting()
    {
        $validatedData = $this->validate([
            'nama_dokumen' => 'required|unique:data_testing,nama_dokumen,' . $this->dataTestingModel->id,
        ]);

        $this->dataTestingModel->update($validatedData);

        session()->flash('message', "Data Testing : {$this->dataTestingModel->nama_dokumen} Berhasil Diperbarui !");
        $this->emit('refreshDatatable');
        $this->reset();
    }

    public function deleteDataTestingConfirmation(DataTestingModel $data_testing)
    {
        $this->dataTestingModel = $data_testing;
        $this->nama_dokumen = $this->dataTestingModel->nama_dokumen;
        $this->openDeleteDataTestingConfirmation = true;
    }

    public function deleteDataTesting()
    {
        $this->dataTestingModel->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DataTestingModel::truncate();
        DataHasil::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        session()->flash('message', "Data Testing {$this->dataTestingModel->nama_dokumen} Berhasil Dihapus !");
        $this->emit('refreshDatatable');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.documents.klasifikasi-k-n-n.data-testing');
    }
}
