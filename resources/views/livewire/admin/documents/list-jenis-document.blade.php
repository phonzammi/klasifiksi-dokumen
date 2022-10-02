<div>
    <x-slot name="header">
        <div class="col-sm-6">
            <h1 class="m-0">Seluruh Dokumen</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Seluruh Dokumen</a></li>
                <li class="breadcrumb-item active">List Jenis Dokumen</li>
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
                    <button wire:click="createJenisDocumentModal" class="btn btn-primary mr-1"
                        wire:loading.attr="disabled">
                        <i class="fas fa-file-upload"></i>
                        Tambah Jenis
                    </button>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Jenis Dokumen</th>
                                        <th scope="col">Hak Akses</th>
                                        <th scope="col">#Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($semua_jenis_dokumen as $jenis_dokumen)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $jenis_dokumen->jenis_dokumen }}</td>
                                            <td>
                                                @forelse ($jenis_dokumen->roles as $hak_akses)
                                                    <span class="badge badge-primary">
                                                        {{ $hak_akses->role_name }}
                                                    </span>
                                                @empty
                                                    <span class="badge badge-warning">
                                                        {{ __('Tidak Tersedia') }}
                                                    </span>
                                                @endforelse
                                            </td>
                                            <td>
                                                <a href="#"
                                                    wire:click='editJenisDocumentModal({{ $jenis_dokumen }})'>
                                                    <i class="far fa-edit mr-1"></i>
                                                </a>
                                                <a href="#">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <div class="alert alert-danger font-weight-bold" role="alert">
                                                    Tidak Tersedia Jenis Dokumen, Silahkan Tambah Baru !
                                                </div>
                                            </td>
                                        </tr>
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
    </div>

    <x-jet-dialog-modal wire:model="openJenisDocumentModal">
        <x-slot name="title">
            @if ($isEditing)
                {{ __('Edit Jenis Dokumen') }}
            @else
                {{ __('Jenis Dokumen Baru') }}
            @endif
        </x-slot>

        <x-slot name="content">
            <form id="jenisDocument">

                <div class="form-group">
                    <x-jet-label for="jenis_dokumen">Jenis Dokumen</x-jet-label>

                    <x-jet-input type='text' placeholder="{{ __('Jenis Dokumen') }}"
                        class="{{ $errors->has('jenis_dokumen') ? 'is-invalid' : '' }}"
                        wire:model.debounce.500ms="jenis_dokumen" wire:keydown.enter="createJenisDocument" />

                    <x-jet-input-error for="jenis_dokumen" class="mt-2" />
                </div>

                <div wire:ignore class="form-group">
                    <x-jet-label for="role_id">Hak Akses</x-jet-label>

                    <select id="role_id" wire:model='hak_akses'
                        class="select2 form-control @error('role_id') is-invalid @enderror" multiple="multiple"
                        style="width: 100%;">
                        @foreach ($roles as $hakAkses)
                            <option value="{{ $hakAkses->id }}">
                                {{ $hakAkses->role_name }}
                            </option>
                        @endforeach
                    </select>

                    <x-jet-input-error for="role_id" class="mt-2" />
                </div>
            </form>

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-danger" wire:click="closeJenisDocumentModal" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </button>

            <button type="submit" class="btn btn-primary"
                wire:click="{{ $isEditing ? 'updateJenisDocument' : 'createJenisDocument' }}"
                wire:loading.attr="disabled">

                @if ($isEditing)
                    {{ __('Simpan') }}
                @else
                    {{ __('Tambah') }}
                @endif
            </button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
@push('scripts')
    <script>
        $('#role_id').select2({
            placeholder: 'Pilih Hak Akses',
            allowClear: true
        }).on('change', function() {
            @this.set('hak_akses', $(this).val());
        });

        window.addEventListener('reset-form', event => {
            $('#role_id').val(null).trigger('change');
        })
    </script>
@endpush
