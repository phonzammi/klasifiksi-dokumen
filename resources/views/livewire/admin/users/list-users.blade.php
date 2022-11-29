<div>
    <x-slot name="header">
        <div class="col-sm-6">
            <h1 class="m-0">Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div><!-- /.col -->
    </x-slot>
    {{-- <div class="content"> --}}
    <div class="container-fluid">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-check-circle mr-1"></i> Success!</strong> {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-end mb-1">
                    <button wire:click="createUserModal" class="btn btn-primary mr-1"><i class="fa fa-plus-circle"></i>
                        Tambah User</button>
                </div>
                <div class="card">
                    <div class="card-body">
                        <livewire:admin.datatable.users-table />
                    </div>
                </div>


            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
    {{--
    </div> --}}
    <!-- /.content -->
    <x-jet-dialog-modal wire:model="openUserModal">
        <x-slot name="title">
            @if ($isEditing)
                {{ __('Edit User') }}
            @else
                {{ __('Tambah User Baru') }}
            @endif
        </x-slot>

        <x-slot name="content">
            <div class="mx-2">
                <div class="form-group">
                    <x-jet-label for="nim_nip">NIM atau NIP</x-jet-label>

                    <x-jet-input type='number' placeholder="{{ __('NIM atau NIP ...') }}"
                        class="{{ $errors->has('nim_nip') ? 'is-invalid' : '' }}" wire:model.debounce.500ms="nim_nip"
                        wire:keydown.enter="create" />

                    <x-jet-input-error for="nim_nip" class="mt-2" />
                </div>

                <div class="form-group">
                    <x-jet-label for="name">Nama Lengkap :</x-jet-label>

                    <x-jet-input type='text' placeholder="{{ __('Nama Lengkap ...') }}"
                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}" wire:model.debounce.500ms="name"
                        wire:keydown.enter="create" />

                    <x-jet-input-error for="name" class="mt-2" />
                </div>

                <div class="form-group">
                    <x-jet-label for="jurusan_id">Jurusan :</x-jet-label>

                    <select id="jurusan_id" wire:model="selectedJurusan"
                        class="custom-select @error('jurusan_id') is-invalid @enderror">
                        <option value="">Pilih Jurusan ...</option>
                        @foreach ($seluruh_jurusan as $jurusan)
                            <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>

                    <x-jet-input-error for="jurusan_id" class="mt-2" />
                </div>

                @if (!is_null($selectedJurusan))
                    <div class="form-group">
                        <x-jet-label for="prodi_id">Prodi :</x-jet-label>

                        <select id="prodi_id" wire:model="prodi_id"
                            class="custom-select @error('prodi_id') is-invalid @enderror">
                            <option value="">Pilih Prodi ...</option>
                            @foreach ($seluruh_prodi as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>

                        <x-jet-input-error for="prodi_id" class="mt-2" />
                    </div>
                @endif

                <div class="form-group">
                    <x-jet-label for="role_id">Hak Akses (Jabatan) :</x-jet-label>

                    <select id="role_id" wire:model.lazy="role_id"
                        class="custom-select @error('role_id') is-invalid @enderror">
                        <option value="">Pilih Jabatan ...</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>

                    <x-jet-input-error for="role_id" class="mt-2" />
                </div>

                <div class="form-group">
                    <x-jet-label for="email">Alamat Email :</x-jet-label>

                    <x-jet-input type='text' placeholder="{{ __('Alamat Email ...') }}"
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}" wire:model.debounce.500ms="email"
                        wire:keydown.enter="create" />

                    <x-jet-input-error for="email" class="mt-2" />
                </div>

                <div class="form-group">
                    <x-jet-label for="password">Kata Sandi {{ $isEditing ? 'Baru' : '' }} :</x-jet-label>

                    <x-jet-input type='password' placeholder="{{ __('Kata Sandi ...') }}"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}" wire:model.debounce.500ms="password"
                        wire:keydown.enter="create" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>

                <div class="form-group">
                    <x-jet-label for="password_confirmation">Konfirmasi Kata Sandi {{ $isEditing ? 'Baru' : '' }} :
                    </x-jet-label>

                    <x-jet-input type='password' placeholder="{{ __('Konfirmasi Kata Sandi ...') }}"
                        class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                        wire:model.debounce.500ms="password_confirmation" wire:keydown.enter="create" />

                    <x-jet-input-error for="password_confirmation" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-danger" wire:click="closeUserModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </button>

            <button type="submit" class="btn btn-primary" wire:click="{{ $isEditing ? 'update' : 'create' }}"
                wire:loading.attr="disabled">

                @if ($isEditing)
                    {{ __('Simpan') }}
                @else
                    {{ __('Tambah') }}
                @endif
            </button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Delete User Confirmation Modal -->
    <x-jet-dialog-modal wire:model="openDeleteUserConfirmation">
        <x-slot name="title">
            {{ __("Hapus User '{$this->name}' ") }}
        </x-slot>

        <x-slot name="content">
            {{ __("Anda yakin ingin menghapus user '{$this->name}'?") }}
            <p class='text-danger font-italic'>
                {{ __("Aksi ini juga akan menghapus seluruh Dokumen yang berhubungan dengan user '{$this->name}'!!!") }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="closeUserModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </x-jet-secondary-button>

            <x-jet-danger-button wire:click="delete" wire:loading.attr="disabled">
                <div wire:loading wire:target="delete" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden"></span>
                </div>

                {{ __('Hapus') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
