<div>
    <x-slot name="header">
        <div class="col-sm-6">
            <h1 class="m-0">List Dokumen</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">List Dokumen</li>
            </ol>
        </div><!-- /.col -->
    </x-slot>

    <div class="container-fluid">
        {{-- @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-check-circle mr-1"></i> Success!</strong> {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif --}}

        <div class="row justify-content-center">
            <div class="col">
                <div class="card border">
                    <div class="card-header d-flex justify-content-between">
                        <div class="mr-auto my-auto">
                            <i class="fas fa-table me-1"></i>
                            {{ __('Seluruh Dokumen') }}
                        </div>

                        <div class="my-auto">
                            <button wire:click="createDocumentModal" class="btn btn-sm btn-primary mr-1"
                                wire:loading.attr="disabled">
                                <i class="fas fa-file-upload"></i>
                                Unggah Dokumen Baru
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
                        <livewire:admin.datatable.documents-table />
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->

    <x-jet-dialog-modal wire:model="openDocumentModal">
        <x-slot name="title">
            @if ($isEditing)
                {{ __('Edit Dokumen') }}
            @else
                {{ __('Unggah Dokumen Baru') }}
            @endif
        </x-slot>

        <x-slot name="content">
            <div class="form-group">
                <x-jet-label for="nama_dokumen">
                    Nama Dokumen
                </x-jet-label>
                <x-jet-input type='text' placeholder="{{ __('Nama Dokumen') }}"
                    class="{{ $errors->has('nama_dokumen') ? 'is-invalid' : '' }}"
                    wire:model.debounce.500ms="nama_dokumen" wire:keydown.enter="storeDocument" />

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

            <div class="form-group">
                <x-jet-label for="lampiran">
                    Pilih Lampiran
                    @if ($isEditing)
                        (Opsional)
                    @endif
                </x-jet-label>
                <div class="custom-file">
                    {{-- <input type="file" class="custom-file-input" id="customFile"> --}}
                    <x-jet-input type='file' wire:model.debounce.500ms="lampiran" id="customFile"
                        class="custom-file-input {{ $errors->has('lampiran') ? 'is-invalid' : '' }}" accept=".pdf" />
                    <label class="custom-file-label"
                        for="customFile">{{ $lampiran ? $lampiran->getClientOriginalName() : 'Pilih Lampiran' }}</label>
                    <x-jet-input-error for="lampiran" class="mt-2" />
                </div>

            </div>

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-danger" wire:click="closeDocumentModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </button>

            <button type="submit" class="btn btn-primary"
                wire:click="{{ $isEditing ? 'updateDocument' : 'storeDocument' }}" wire:loading.attr="disabled">

                @if ($isEditing)
                    {{ __('Simpan') }}
                @else
                    {{ __('Unggah') }}
                @endif
            </button>
        </x-slot>

    </x-jet-dialog-modal>

    <!-- Delete  Dokumen Confirmation Modal -->
    <x-jet-dialog-modal wire:model="openDeleteDocumentConfirmation">
        <x-slot name="title">
            {{ __("Hapus Dokumen '{$this->nama_dokumen}' ") }}
        </x-slot>

        <x-slot name="content">
            {{ __("Anda yakin ingin menghapus dokumen '{$this->nama_dokumen}'?") }}
            <p class='text-danger font-italic'>
                {{ __("Aksi ini juga akan menghapus seluruh data yang berhubungan dengan Dokumen '{$this->nama_dokumen}'!!!") }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="closeDocumentModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </x-jet-secondary-button>

            <x-jet-danger-button wire:click="deleteDocument" wire:loading.attr="disabled">
                <div wire:loading wire:target="deleteDocument" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden"></span>
                </div>

                {{ __('Hapus') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
