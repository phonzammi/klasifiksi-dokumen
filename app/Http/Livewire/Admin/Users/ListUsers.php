<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ListUsers extends Component
{
    public $nim_nip, $name, $email, $password, $password_confirmation, $role_id, $prodi_id;
    public $openUserModal = false;
    public $isEditing = false;
    public $openDeleteUserConfirmation = false;
    public $userModel;
    public $seluruh_jurusan;
    public $seluruh_prodi;

    public $selectedJurusan = NULL;
    public $selectedProdi = NULL;

    public function mount()
    {
        $this->seluruh_jurusan = Jurusan::all();
        $this->seluruh_prodi = collect();
    }

    protected $listeners = ['editUserModal', 'deleteUserConfirmation', 'closeModal' => 'closeUserModal'];

    protected $rules = [
        'nim_nip' => 'required|numeric|unique:users,nim_nip',
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'password_confirmation' => 'required|same:password',
        'role_id' => 'nullable',
        'prodi_id' => 'nullable|numeric'
    ];

    protected $validationAttributes = [
        'nim_nip' => 'NIM atau NIP',
        'name' => 'Nama Lengkap',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
        'password_confirmation' => 'Konfirmasi Kata Sandi',
        'role_id' => "Jabatan",
        'jabatan_id' => 'Jabatan',
        'prodi_id' => 'Prodi'
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields, [
            'nim_nip' => ['required', 'numeric', Rule::unique('users')->ignore($this->userModel)],
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userModel)],
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'prodi_id' => [Rule::requiredIf(!is_null($this->selectedJurusan)), 'numeric'],
            'role_id' => 'nullable|numeric',
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
            $this->selectedJurusan = NULL;
            $this->prodi_id = "";
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

        $this->userModel = $user->loadMissing('prodi');

        $this->nim_nip = $this->userModel->nim_nip;
        $this->name = $this->userModel->name;
        $this->email = $this->userModel->email;
        $this->role_id = $this->userModel->role_id;
        $this->prodi_id = $this->userModel->prodi_id;
        $this->selectedJurusan = $this->userModel->prodi ? $this->userModel->prodi->jurusan_id : NULL;
        $this->updatedSelectedJurusan($this->selectedJurusan);
    }

    public function update()
    {
        $validatedData = $this->validate([
            'nim_nip' => ['required', 'numeric', Rule::unique('users')->ignore($this->userModel)],
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userModel)],
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|same:password',
            'prodi_id' => [Rule::requiredIf(!is_null($this->selectedJurusan)), 'numeric'],
            'role_id' => 'nullable|numeric',
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
        $this->selectedJurusan = NULL;
        $this->prodi_id = "";
        $this->openUserModal = false;
        $this->isEditing = false;
        $this->userModel = "";
        $this->emit('refreshDatatable');
    }

    public function create()
    {
        $validatedData = $this->validate();
        $validatedData['password'] = bcrypt($this->password);
        unset($validatedData['password_confirmation']);

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

    public function updatedSelectedJurusan($jurusan)
    {
        $this->seluruh_prodi = Prodi::where('jurusan_id', $jurusan)->get();
        $this->selectedProdi = NULL;
        $this->prodi_id = "";
        if (!$jurusan) {
            $this->selectedJurusan = NULL;
        }
    }
}
