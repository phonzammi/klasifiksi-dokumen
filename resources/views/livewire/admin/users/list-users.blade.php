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
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">NIM/NIP</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Jabatan (Hak Akses)</th>
                                        <th scope="col">#Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td class="{{ !$user->nim_nip ? 'text-danger' : '' }}">
                                                {{ $user->nim_nip ?? 'Tidak Tersedia' }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                {{-- {{ $user->has('role') ? $user->role->role_name : 'Tidak Tersedia' }} --}}
                                                @if ($user->has('role'))
                                                    @if ($user->role->role_id = App\Models\Role::IS_DOSEN)
                                                        <span class="badge badge-primary">
                                                            {{ $user->role->role_name }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-success">
                                                            {{ $user->role->role_name }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-danger">
                                                        {{ __('Tidak Tersedia') }}
                                                    </span>
                                                @endif
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
                                    @endforeach

                                    {{-- @endforelse --}}
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
    {{-- </div> --}}
    <!-- /.content -->
    <!-- Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <form autocomplete="off" wire:submit.prevent="createUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">Tambah User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">NIM atau NIP :</label>
                            <input type="number" wire:model="nim_nip"
                                class="form-control @error('nim_nip') is-invalid @enderror"
                                placeholder="NIM atau NIP ..." id="nim_nip">
                            @error('nim_nip')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Lengkap :</label>
                            <input type="text" wire:model="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap ..."
                                id="name">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="role_id">Jabatan :</label>
                            <select id="role_id" wire:model.lazy="role_id"
                                class="custom-select @error('role_id') is-invalid @enderror">
                                <option value="">Pilih Jabatan ...</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Alamat Email :</label>
                            <input type="email" wire:model="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="Alamat Email ..."
                                id="email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Kata Sandi :</label>
                            <input type="password" wire:model='password'
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Kata Sandi ..." id="password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="passwordConfirmation">Konfirmasi Kata Sandi :</label>
                            <input type="password" wire:model='password_confirmation'
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Konfirmasi Kata Sandi ...">
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        window.addEventListener('show-create-user-modal', event => {
            $("#createUserModal").modal('show');
        })

        window.addEventListener('close-create-user-modal', event => {
            $("#createUserModal").modal('hide');
        })

    </script>
@endpush
