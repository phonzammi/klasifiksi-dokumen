<div class="col-md-6">
    <div class="card border">
        <div class="card-header d-flex justify-content-between">
            <div class="mr-auto my-auto">
                <i class="fas fa-table me-1"></i>
                {{ __('Data Testing') }}
            </div>

            <div class="my-auto">
                <button class="btn btn-sm btn-primary text-nowrap" wire:click="createDataTestingModal">
                    <i class="nav-icon fas fa-plus-circle"></i>
                    Tambah Data Testing
                </button>
            </div>
        </div>

        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-check-circle mr-1"></i> Success!</strong>
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-check-circle mr-1"></i> Failed!</strong>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <livewire:admin.datatable.klasifikasi-k-n-n.data-testing-table />
        </div>
    </div>

    <x-jet-dialog-modal wire:model="openDataTestingModal">
        <x-slot name="title">
            @if ($isEditing)
                {{ __('Edit Data Testing') }}
            @else
                {{ __('Tambah Data Testing Baru') }}
            @endif
        </x-slot>

        <x-slot name="content">
            <div class="form-group">
                <x-jet-label for="nama_dokumen">
                    Nama Dokumen
                </x-jet-label>
                <x-jet-input type='text' placeholder="{{ __('Nama Dokumen') }}"
                    class="{{ $errors->has('nama_dokumen') ? 'is-invalid' : '' }}"
                    wire:model.debounce.500ms="nama_dokumen"
                    wire:keydown.enter="{{ $isEditing ? 'updateDataTesting' : 'storeDataTesting' }}" />

                <x-jet-input-error for="nama_dokumen" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-danger" wire:click="closeDataTestingModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </button>

            <button type="submit" class="btn btn-primary"
                wire:click="{{ $isEditing ? 'updateDataTesting' : 'storeDataTesting' }}" wire:loading.attr="disabled">

                @if ($isEditing)
                    {{ __('Simpan') }}
                @else
                    {{ __('Tambah') }}
                @endif
            </button>
        </x-slot>

    </x-jet-dialog-modal>

    <!-- Delete  Dokumen Confirmation Modal -->
    <x-jet-dialog-modal wire:model="openDeleteDataTestingConfirmation">
        <x-slot name="title">
            {{ __("Hapus Dokumen '{$this->nama_dokumen}' ") }}
        </x-slot>

        <x-slot name="content">
            {{ __("Anda yakin ingin menghapus dokumen '{$this->nama_dokumen}'?") }}
            <p class='text-danger font-italic'>
                {{ __('Aksi ini juga akan menghapus Data Hasil') }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="closeDataTestingModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </x-jet-secondary-button>

            <x-jet-danger-button wire:click="deleteDataTesting" wire:loading.attr="disabled">
                <div wire:loading wire:target="deleteDataTesting" class="spinner-border spinner-border-sm"
                    role="status">
                    <span class="visually-hidden"></span>
                </div>

                {{ __('Hapus') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
