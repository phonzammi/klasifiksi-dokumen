<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border">
                    <div class="card-header d-flex justify-content-between">
                        <div class="my-auto">
                            <i class="fas fa-table me-1"></i>
                            {{ __('Seluruh Dokumen') }}
                        </div>

                        <button wire:click="createDocumentModal" class="btn btn-sm btn-primary mr-1"
                            wire:loading.attr="disabled">
                            <i class="fas fa-file-upload"></i>
                            Unggah Dokumen Baru
                        </button>
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
                        <livewire:users.datatable.user-documents-table />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-jet-dialog-modal wire:model="openDocumentModal">
        <x-slot name="title">
            {{ __('Unggah Dokumen Baru') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Silahkan isi form berikut.') }}

            <div class="mt-3">
                <x-jet-label for="nama_dokumen">
                    Nama Dokumen
                </x-jet-label>
                <x-jet-input type='text' placeholder="{{ __('Nama Dokumen') }}"
                    class="{{ $errors->has('nama_dokumen') ? 'is-invalid' : '' }}"
                    wire:model.debounce.500ms="nama_dokumen" wire:keydown.enter="store" />

                <x-jet-input-error for="nama_dokumen" class="mt-2" />
            </div>

            <div class="mt-3">
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

            <div class="mt-3">
                <x-jet-label for="lampiran">
                    Pilih Lampiran
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

            <button type="submit" class="btn btn-primary" wire:click="store" wire:loading.attr="disabled">
                {{ __('Unggah') }}
            </button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
