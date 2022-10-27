<?php

namespace App\Http\Livewire\Admin\Documents;

use App\Models\JenisDokumen;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ListJenisDocument extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $jenis_dokumen, $jenisDokumenModel, $jenisDokumenId;
    public $selectedRoles = [];
    public $openJenisDocumentModal = false;
    public $openDeleteJenisDocumentModal = false;
    public $isEditing = false;
    public $view;
    public $upload;
    public $download;

    protected $rules = [
        'jenis_dokumen' => 'required|unique:jenis_dokumen,jenis_dokumen'
    ];

    protected $validationAttributes = [
        'jenis_dokumen' => 'Jenis Dokumen'
    ];

    public function updated($fields)
    {
        $this->jenis_dokumen = Str::title($this->jenis_dokumen);

        $this->validateOnly($fields, [
            'jenis_dokumen' => ['required', Rule::unique('jenis_dokumen')->ignore($this->jenisDokumenModel)],
        ]);
    }

    public function selectRole($role_id)
    {
        $this->view[$role_id] = false;
        $this->upload[$role_id] = false;
        $this->download[$role_id] = false;
    }

    // public function resetInput()
    // {
    //     $this->jenisDokumenId = "";
    //     $this->jenis_dokumen = "";
    //     $this->selectedRoles = [];
    //     $this->openJenisDocumentModal = false;
    //     $this->openDeleteJenisDocumentModal = false;
    //     $this->isEditing = false;
    //     $this->jenisDokumenModel = "";
    //     $this->view = "";
    //     $this->upload = "";
    //     $this->download = "";
    // }

    public function closeJenisDocumentModal()
    {
        $this->openJenisDocumentModal = false;
        $this->isEditing = false;
        $this->selectedRoles = [];
        $this->view = null;
        $this->upload = null;
        $this->download = null;
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
        $this->reset();
    }

    public function createJenisDocument()
    {
        $validatedData = $this->validate();
        $jenis_dokumen_baru = JenisDokumen::create($validatedData);

        if (count($this->selectedRoles) > 0) {
            foreach ($this->selectedRoles as $role_id) {
                // dd($this->view[$role_id]);
                $jenis_dokumen_baru->roles()
                    ->attach($role_id, [
                        'view' => $this->view[$role_id],
                        'upload' => $this->upload[$role_id],
                        'download' => $this->download[$role_id]
                    ]);
            }
        }

        session()->flash('message', "Jenis Dokumen Baru Berhasil Ditambahkan !");
        $this->reset();
    }

    public function editJenisDocumentModal(JenisDokumen $jenis_dokumen)
    {
        $this->openJenisDocumentModal = true;
        $this->isEditing = true;

        $this->jenisDokumenModel = $jenis_dokumen->loadMissing(['roles']);
        $this->jenis_dokumen = $this->jenisDokumenModel->jenis_dokumen;
        $this->selectedRoles = $this->jenisDokumenModel->roles->pluck('id')->toArray();
        foreach ($this->jenisDokumenModel->roles as $role) {
            $this->view[$role->id] = $role->pivot->view;
            $this->upload[$role->id] = $role->pivot->upload;
            $this->download[$role->id] = $role->pivot->download;
        }
    }

    public function updateJenisDocument()
    {
        // dd($this->upload);
        $validatedData = $this->validate([
            'jenis_dokumen' => 'required|unique:jenis_dokumen,jenis_dokumen,' . $this->jenisDokumenModel->id
        ]);

        $this->jenisDokumenModel->update($validatedData);
        if (count($this->selectedRoles) > 0) {
            $this->jenisDokumenModel->roles()->detach();
            foreach ($this->selectedRoles as $role_id) {
                // var_dump(intval($role_id));
                $this->jenisDokumenModel->roles()
                    ->attach($role_id, [
                        'view' => $this->view[$role_id],
                        'upload' => $this->upload[$role_id],
                        'download' => $this->download[$role_id]
                    ]);
            }
        }
        // // $this->jenisDokumenModel->roles()->sync($this->selectedRoles);
        session()->flash('message', "Jenis Dokumen Berhasil Diperbarui !");
        $this->reset();
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
