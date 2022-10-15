<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ListUsers extends Component
{
    public $nim_nip, $name, $email, $password, $password_confirmation, $role_id;
    public $openUserModal = false;
    public $isEditing = false;
    public $openDeleteUserConfirmation = false;
    public $userModel;

    protected $listeners = ['editUserModal', 'deleteUserConfirmation', 'closeModal' => 'closeUserModal'];

    protected $rules = [
        'nim_nip' => 'required|numeric|unique:users,nim_nip',
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'password_confirmation' => 'required|same:password',
        'role_id' => 'nullable',
    ];

    protected $validationAttributes = [
        'nim_nip' => 'NIM atau NIP',
        'name' => 'Nama Lengkap',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
        'password_confirmation' => 'Konfirmasi Kata Sandi',
        'role_id' => "Jabatan"
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields, [
            'nim_nip' => ['required', 'numeric', Rule::unique('users')->ignore($this->userModel)],
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userModel)],
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|same:password',
            'role_id' => 'nullable',
        ]);
    }

    public function createUserModal()
    {
        $this->openUserModal = true;
        $this->isEditing = false;
    }

    public function closeUserModal()
    {
        if ($this->isEditing || $this->openDeleteUserConfirmation) {
            $this->nim_nip = "";
            $this->name = "";
            $this->email = "";
            $this->password = "";
            $this->password_confirmation = "";
            $this->role_id = "";
        }
        $this->userModel = "";
        $this->openUserModal = false;
        $this->isEditing = false;
        $this->openDeleteUserConfirmation = false;
    }

    public function editUserModal(User $user)
    {
        $this->openUserModal = true;
        $this->isEditing = true;

        $this->userModel = $user;

        $this->nim_nip = $this->userModel->nim_nip;
        $this->name = $this->userModel->name;
        $this->email = $this->userModel->email;
        $this->role_id = $this->userModel->role_id;
    }

    public function update()
    {
        $validatedData = $this->validate([
            'nim_nip' => ['required', 'numeric', Rule::unique('users')->ignore($this->userModel)],
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userModel)],
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|same:password',
            'role_id' => 'nullable',
        ]);
        if ($this->password != "") {
            $validatedData['password'] = bcrypt($this->password);
        }
        if ($this->password == "") {
            unset($validatedData['password']);
        }
        $this->userModel->update($validatedData);
        session()->flash('message', "User berhasil disimpan !");
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->nim_nip = "";
        $this->name = "";
        $this->email = "";
        $this->password = "";
        $this->password_confirmation = "";
        $this->role_id = "";
        $this->openUserModal = false;
        $this->isEditing = false;
        $this->userModel = "";
        $this->emit('refreshDatatable');
    }

    public function create()
    {
        $validatedData = $this->validate();
        $validatedData['password'] = bcrypt($this->password);

        User::create($validatedData);
        session()->flash('message', "User berhasil ditambahkan !");
        $this->resetInput();
    }

    public function deleteUserConfirmation(User $user)
    {
        $this->userModel = $user;
        $this->name = $this->userModel->name;
        $this->openDeleteUserConfirmation = true;
    }

    public function delete()
    {
        $this->userModel->delete();
        session()->flash('message', "User '{$this->userModel->name}' berhasil Dihapus ! !");
        $this->openDeleteUserConfirmation = false;
        $this->resetInput();
    }

    public function render()
    {
        $roles = Role::all();
        $users = User::where("is_admin", 0)->with('role')->latest()->paginate();
        return view('livewire.admin.users.list-users', compact('users', 'roles'));
    }
}
