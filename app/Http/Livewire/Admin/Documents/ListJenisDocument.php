<?php

namespace App\Http\Livewire\Admin\Documents;

use App\Models\JenisDokumen;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class ListJenisDocument extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $jenis_dokumen, $jenisDokumenModel, $jenisDokumenId;
    public $selectedRoles = [];
    public $openJenisDocumentModal = false;
    public $openDeleteJenisDocumentModal = false;
    public $isEditing = false;

    protected $rules = [
        'jenis_dokumen' => 'required|unique:jenis_dokumen,jenis_dokumen'
    ];

    protected $validationAttributes = [
        'jenis_dokumen' => 'Jenis Dokumen'
    ];

    // public function updated($fields)
    // {
    //     $this->validateOnly($fields);
    // }

    public function resetInput()
    {
        $this->jenisDokumenId = "";
        $this->jenis_dokumen = "";
        $this->selectedRoles = [];
        $this->openJenisDocumentModal = false;
        $this->openDeleteJenisDocumentModal = false;
        $this->isEditing = false;
        $this->jenisDokumenModel = "";
    }

    public function closeJenisDocumentModal()
    {
        $this->openJenisDocumentModal = false;
        $this->isEditing = false;
        $this->selectedRoles = [];
    }

    public function closeDeleteJenisDocumentModal()
    {
        $this->jenisDokumenId = "";
        $this->jenis_dokumen = "";
        $this->openDeleteJenisDocumentModal = false;
    }

    public function createJenisDocumentModal()
    {
        $this->openJenisDocumentModal = true;
        $this->isEditing = false;
    }

    public function deleteJenisDocumentModal(JenisDokumen $jenis_dokumen)
    {
        $this->jenisDokumenId = $jenis_dokumen->id;
        $this->jenis_dokumen = $jenis_dokumen->jenis_dokumen;
        $this->openDeleteJenisDocumentModal = true;
        $this->isEditing = false;
    }

    public function deleteJenisDocument()
    {
        $jenis_dokumen = JenisDokumen::find($this->jenisDokumenId);
        $this->jenis_dokumen = $jenis_dokumen->jenis_dokumen;
        $jenis_dokumen->delete();

        session()->flash('message', "Jenis Dokumen '{$jenis_dokumen->jenis_dokumen}' berhasil Dihapus ! !");
        $this->resetInput();
    }

    public function createJenisDocument()
    {
        $validatedData = $this->validate();
        $jenis_dokumen_baru = JenisDokumen::create($validatedData);

        if (count($this->selectedRoles) > 0) {
            $jenis_dokumen_baru->roles()->attach($this->selectedRoles);
        }

        session()->flash('message', "Jenis Dokumen Baru Berhasil Ditambahkan !");
        $this->resetInput();
    }

    public function editJenisDocumentModal(JenisDokumen $jenis_dokumen)
    {
        $this->openJenisDocumentModal = true;
        $this->isEditing = true;

        $this->jenisDokumenModel = $jenis_dokumen->loadMissing(['roles']);
        $this->jenis_dokumen = $jenis_dokumen->jenis_dokumen;
        $this->selectedRoles = $this->jenisDokumenModel->roles->pluck('id')->toArray();
        // dd($this->jenisDokumenModel->roles->pluck('id')->toArray());
    }

    public function updateJenisDocument()
    {
        $validatedData = $this->validate([
            'jenis_dokumen' => 'required|unique:jenis_dokumen,jenis_dokumen,' . $this->jenisDokumenModel->id
        ]);

        $this->jenisDokumenModel->update($validatedData);
        $this->jenisDokumenModel->roles()->sync($this->selectedRoles);
        session()->flash('message', "Jenis Dokumen Berhasil Diperbarui !");
        $this->resetInput();
    }

    public function render()
    {
        $roles = Role::all();
        $semua_jenis_dokumen = JenisDokumen::with('roles')->paginate(10);
        return view('livewire.admin.documents.list-jenis-document', [
            'roles' => $roles,
            'semua_jenis_dokumen' => $semua_jenis_dokumen
        ]);
    }
}
