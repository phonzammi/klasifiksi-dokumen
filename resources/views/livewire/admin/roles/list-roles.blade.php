<div>
    <x-slot name="header">
        <div class="col-sm-6">
            <h1 class="m-0">List Hak Akses (Jabatan)</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">List Hak Akses (Jabatan)</li>
            </ol>
        </div><!-- /.col -->
    </x-slot>

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
            <div class="col">
                <div class="card border">
                    <div class="card-header d-flex justify-content-between">
                        <div class="mr-auto my-auto">
                            <i class="fas fa-table me-1"></i>
                            {{ __('Seluruh Hak Akses (Jabatan)') }}
                        </div>

                        <div class="my-auto">
                            <button wire:click="createRoleModal" class="btn btn-sm btn-primary mr-1"
                                wire:loading.attr="disabled">
                                <i class="nav-icon fas fa-plus"></i>
                                Tambah Hak Akses (Jabatan) Baru
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <livewire:admin.datatable.roles-table />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-jet-dialog-modal wire:model="openRoleModal">
        <x-slot name="title">
            @if ($isEditing)
            {{ __('Edit Hak Akses') }}
            @else
            {{ __('Hak Akses Baru') }}
            @endif
        </x-slot>

        <x-slot name="content">
            {{-- <form id="jenisDocument"> --}}

                <div class="form-group">
                    <x-jet-label for="role_name">Hak Akses (Jabatan)</x-jet-label>

                    <x-jet-input type='text' placeholder="{{ __('Hak Akses (Jabatan)') }}"
                        class="{{ $errors->has('role_name') ? 'is-invalid' : '' }}"
                        wire:model.debounce.500ms="role_name" wire:keydown.enter="store" />

                    <x-jet-input-error for="role_name" class="mt-2" />
                </div>

                {{--
            </form> --}}

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-danger" wire:click="closeRoleModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </button>

            <button type="submit" class="btn btn-primary" wire:click="{{ $isEditing ? 'update' : 'store' }}"
                wire:loading.attr="disabled">

                @if ($isEditing)
                {{ __('Simpan') }}
                @else
                {{ __('Tambah') }}
                @endif
            </button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Delete Hak Akses Confirmation Modal -->
    <x-jet-dialog-modal wire:model="openDeleteRoleConfirmation">
        <x-slot name="title">
            {{ __("Hapus Hak Akses '{$this->role_name}' ") }}
        </x-slot>

        <x-slot name="content">
            {{ __("Anda yakin ingin menghapus Hak Akses '{$this->role_name}'?") }}
            <p class='text-danger font-italic'>
                {{ __("Aksi ini juga akan menghapus seluruh Anggota/User yang berhubungan dengan Hak Akses
                '{$this->role_name}'!!!") }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="closeRoleModal" wire:loading.attr="disabled">
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
