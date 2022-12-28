<div class="col-md-6">
    <div class="card border">
        <div class="card-header d-flex justify-content-between">
            <div class="mr-auto my-auto">
                <i class="fas fa-table me-1"></i>
                {{ __('Data Training') }}
            </div>

            <div class="my-auto">
                <button class="btn btn-sm btn-danger text-nowrap" wire:click="hapusSemuaData">
                    <i class="nav-icon fas fa-trash"></i>
                    Hapus Semua
                </button>
                <button class="btn btn-sm btn-primary text-nowrap" wire:click="createDataTrainingModal">
                    <i class="nav-icon fas fa-plus-circle"></i>
                    Tambah Data Training
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
            <livewire:admin.datatable.klasifikasi-k-n-n.data-training-table />
        </div>
    </div>

    <x-jet-dialog-modal wire:model="openDataTrainingModal">
        <x-slot name="title">
            @if ($isEditing)
                {{ __('Edit Data Training') }}
            @else
                {{ __('Tambah Data Training Baru') }}
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
                    wire:keydown.enter="{{ $isEditing ? 'updateDataTraining' : 'storeDataTraining' }}" />

                <x-jet-input-error for="nama_dokumen" class="mt-2" />
            </div>

            <div class="form-group">
                <x-jet-label for="jenis_dokumen_id">
                    Jenis Dokumen
                </x-jet-label>
                <select id="jenis_dokumen_id" wire:model.lazy="jenis_dokumen_id"
                    class="custom-select {{ $errors->has('jenis_dokumen_id') ? 'is-invalid' : '' }}">
                    <option value="">Pilih Jenis Dokumen ...</option>
                    @foreach ($semua_jenis_dokumen as $jenis_dokumen)
                        <option value="{{ $jenis_dokumen->id }}">{{ $jenis_dokumen->jenis_dokumen }}</option>
                    @endforeach
                </select>

                <x-jet-input-error for="jenis_dokumen_id" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-danger" wire:click="closeDataTrainingModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </button>

            <button type="submit" class="btn btn-primary"
                wire:click="{{ $isEditing ? 'updateDataTraining' : 'storeDataTraining' }}"
                wire:loading.attr="disabled">

                @if ($isEditing)
                    {{ __('Simpan') }}
                @else
                    {{ __('Tambah') }}
                @endif
            </button>
        </x-slot>

    </x-jet-dialog-modal>

    <!-- Delete  Dokumen Confirmation Modal -->
    <x-jet-dialog-modal wire:model="openDeleteDataTrainingConfirmation">
        <x-slot name="title">
            {{ __("Hapus Dokumen '{$this->nama_dokumen}' ") }}
        </x-slot>

        <x-slot name="content">
            {{ __("Anda yakin ingin menghapus Data Training '{$this->nama_dokumen}'?") }}
            {{-- <p class='text-danger font-italic'>
                {{ __("Aksi ini juga akan menghapus seluruh data yang berhubungan dengan Dokumen '{$this->nama_dokumen}'!!!") }}
            </p> --}}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="closeDataTrainingModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </x-jet-secondary-button>

            <x-jet-danger-button wire:click="deleteDataTraining" wire:loading.attr="disabled">
                <div wire:loading wire:target="deleteDataTraining" class="spinner-border spinner-border-sm"
                    role="status">
                    <span class="visually-hidden"></span>
                </div>

                {{ __('Hapus') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
