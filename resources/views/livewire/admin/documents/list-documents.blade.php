<div>
    <x-slot name="header">
        <div class="col-sm-6">
            <h1 class="m-0">Seluruh Dokumen</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Seluruh Dokumen</li>
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
            <div class="col-lg-12">
                <div class="d-flex justify-content-end mb-1">
                    <button wire:click="createDocumentModal" class="btn btn-primary mr-1" wire:loading.attr="disabled">
                        <i class="fas fa-file-upload"></i>
                        Unggah Dokumen
                    </button>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Nama Dokumen</th>
                                        <th scope="col">Jenis Dokumen</th>
                                        <th scope="col">Diunggah Oleh</th>
                                        <th scope="col">#Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @forelse ($semua_dokumen as $dokumen)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $dokumen->nama_dokumen }}</td>
                                            <td>{{ $dokumen->jenis_dokumen->jenis_dokumen }}</td>
                                            <td>{{ $dokumen->uploaded_by->name }}
                                                ({{ $dokumen->uploaded_by->is_admin ? 'Admin' : '' }})
                                            </td>
                                            <td>
                                                <a href="#">
                                                    <i class="far fa-edit mr-1"></i>
                                                </a>
                                                <a href="#">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->

    <x-jet-dialog-modal wire:model="openDocumentModal">
        <x-slot name="title">
            {{ __('Unggah Dokumen Baru') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Silahkan isi form berikut.') }}

            <div class="mt-3 w-md-75">
                <x-jet-label for="nama_dokumen">
                    Nama Dokumen
                </x-jet-label>
                <x-jet-input type='text' placeholder="{{ __('Nama Dokumen') }}"
                    class="{{ $errors->has('nama_dokumen') ? 'is-invalid' : '' }}" wire:model.defer="nama_dokumen"
                    wire:keydown.enter="storeDocument" />

                <x-jet-input-error for="nama_dokumen" class="mt-2" />
            </div>

            <div class="mt-3 w-md-75">
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

            <div class="mt-3 w-md-75">
                <x-jet-label for="lampiran">
                    Pilih Lampiran
                </x-jet-label>
                <div class="custom-file">
                    {{-- <input type="file" class="custom-file-input" id="customFile"> --}}
                    <x-jet-input type='file' wire:model.defer="lampiran" id="customFile"
                        class="custom-file-input {{ $errors->has('lampiran') ? 'is-invalid' : '' }}" accept=".pdf" />
                    <label class="custom-file-label"
                        for="customFile">{{ $lampiran ? $lampiran->getClientOriginalName() : 'Pilih Lampiran' }}</label>
                    <x-jet-input-error for="lampiran" class="mt-2" />
                </div>

            </div>

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-danger" wire:click="$toggle('openDocumentModal')" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </button>

            <button type="submit" class="btn btn-primary" wire:click="storeDocument" wire:loading.attr="disabled">
                {{ __('Unggah') }}
            </button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
