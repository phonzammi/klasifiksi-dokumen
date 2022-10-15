<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class ListUsers extends Component
{
    public $nim_nip, $name, $email, $password, $password_confirmation, $role_id;

    protected $rules = [
        'nim_nip' => 'required|numeric',
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
        $this->validateOnly($fields);
    }

    public function createUserModal()
    {
        $this->dispatchBrowserEvent('show-create-user-modal');
    }

    public function resetInput()
    {
        $this->name = "";
        $this->email = "";
        $this->password = "";
        $this->password_confirmation = "";
        $this->role_id = "";

        $this->dispatchBrowserEvent('close-create-user-modal');
    }

    public function createUser()
    {
        $validatedData = $this->validate();
        $validatedData['password'] = bcrypt($this->password);

        User::create($validatedData);
        session()->flash('message', "User berhasil ditambahkan !");
        $this->resetInput();
    }
    public function render()
    {
        $roles = Role::all();
        $users = User::where("is_admin", 0)->with('role')->latest()->paginate();
        return view('livewire.admin.users.list-users', compact('users', 'roles'));
    }
}
