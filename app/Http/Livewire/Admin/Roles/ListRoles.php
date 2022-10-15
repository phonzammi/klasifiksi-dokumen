<?php

namespace App\Http\Livewire\Admin\Roles;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ListRoles extends Component
{
    public $openRoleModal = false;
    public $isEditing = false;
    public $openDeleteRoleConfirmation = false;
    public $role_name, $roleModel;

    protected $listeners = ['editRoleModal', 'deleteRoleConfirmation'];

    protected $rules = [
        'role_name' => 'required|max:50|unique:roles,role_name',
    ];

    protected $validationAttributes = [
        'role_name' => 'Nama Hak Akses',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields, [
            'role_name' => ['required', 'max:50', Rule::unique('roles')->ignore($this->roleModel)]
        ]);
    }

    public function resetInput()
    {
        $this->role_name = "";
        // $this->openRoleModal = false;
        // $this->isEditing = false;
        $this->roleModel = "";
        $this->closeRoleModal();
        $this->emit('refreshDatatable');
    }

    public function createRoleModal()
    {
        $this->openRoleModal = true;
        $this->isEditing = false;
    }

    public function store()
    {
        $validatedData = $this->validate();
        Role::create($validatedData);
        session()->flash('message', "Hak Akses (Jabatan) Baru Berhasil Ditambahkan !");
        $this->resetInput();
    }

    public function editRoleModal(Role $role)
    {
        $this->openRoleModal = true;
        $this->isEditing = true;
        $this->roleModel = $role;
        $this->role_name = $role->role_name;
    }

    public function update()
    {
        $validatedData = $this->validate([
            'role_name' => ['required', 'max:50', Rule::unique('roles')->ignore($this->roleModel)]
        ]);

        $this->roleModel->update($validatedData);
        session()->flash('message', "Hak Akses (Jabatan) '{$this->roleModel->role_name}' Berhasil Diperbarui !");
        $this->resetInput();
    }

    public function deleteRoleConfirmation(Role $role)
    {
        $this->roleModel = $role;
        $this->role_name = $role->role_name;
        $this->openDeleteRoleConfirmation = true;
    }

    public function delete()
    {
        $this->roleModel->delete();
        session()->flash('message', "Hak Akses '{$this->roleModel->role_name}' berhasil Dihapus ! !");
        $this->resetInput();
    }

    public function closeRoleModal()
    {
        $this->openRoleModal = false;
        $this->isEditing = false;
        $this->openDeleteRoleConfirmation = false;
    }


    public function render()
    {
        return view('livewire.admin.roles.list-roles');
    }
}
