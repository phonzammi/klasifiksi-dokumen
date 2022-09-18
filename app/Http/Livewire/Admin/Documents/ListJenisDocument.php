<?php

namespace App\Http\Livewire\Admin\Documents;

use App\Models\JenisDokumen;
use App\Models\Role;
use Livewire\Component;

class ListJenisDocument extends Component
{
    public $jenis_dokumen, $jenisDokumenModel;
    public $hak_akses = [];
    public $openJenisDocumentModal = false;
    public $isEditing = false;

    protected $rules = [
        'jenis_dokumen' => 'required|unique:jenis_dokumen,jenis_dokumen',
        'hak_akses' => 'nullable',
    ];

    protected $messages = [
        'required' => ':attribute Tidak Boleh Kosong !.',
        'unique' => ':attribute Sudah Pernah Terdaftar !',

    ];

    protected $validationAttributes = [
        'jenis_dokumen' => 'Jenis Dokumen',
        'hak_akses' => 'Hak Akses'
    ];

    // public function updated($fields)
    // {
    //     $this->validateOnly($fields);
    // }

    public function resetInput()
    {
        $this->jenis_dokumen = "";
        $this->hak_akses = [];
        $this->openJenisDocumentModal = false;
        $this->isEditing = false;
        $this->jenisDokumenModel = "";
    }

    public function closeJenisDocumentModal()
    {
        $this->openJenisDocumentModal = false;
        $this->isEditing = false;
        $this->hak_akses = [];
    }

    public function createJenisDocumentModal()
    {
        $this->openJenisDocumentModal = true;
        $this->isEditing = false;
    }

    public function createJenisDocument()
    {
        $validatedData = $this->validate();
        $jenis_dokumen_baru = JenisDokumen::create($validatedData);
        if (count($this->hak_akses) > 0) {
            // foreach ($this->hak_akses as $hak_akses) {
            $jenis_dokumen_baru->roles()->attach($this->hak_akses);
            // }
        }

        session()->flash('message', "Jenis Dokumen Baru Berhasil Ditambahkan !");
        $this->resetInput();
    }

    public function editJenisDocumentModal(JenisDokumen $jenis_dokumen)
    {

        $this->openJenisDocumentModal = true;
        $this->isEditing = true;

        $this->jenisDokumenModel = $jenis_dokumen;
        $this->jenis_dokumen = $jenis_dokumen->jenis_dokumen;
    }

    public function updateJenisDocument()
    {
        $validatedData = $this->validate([
            'jenis_dokumen' => 'required|unique:jenis_dokumen,jenis_dokumen,' . $this->jenisDokumenModel->id,
            'hak_akses' => 'nullable',
        ]);

        $this->jenisDokumenModel->update($validatedData);
        if (count($this->hak_akses) > 0) {
            $this->jenisDokumenModel->roles()->sync($this->hak_akses);
        }
        session()->flash('message', "Jenis Dokumen Berhasil Diperbarui !");
        $this->resetInput();
    }

    public function render()
    {
        $roles = Role::all();
        $semua_jenis_dokumen = JenisDokumen::with('roles')->get();
        return view('livewire.admin.documents.list-jenis-document', compact('roles', 'semua_jenis_dokumen'));
    }
}
