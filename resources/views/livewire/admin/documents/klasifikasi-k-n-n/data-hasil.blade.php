<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card border">
                <div class="card-header d-flex justify-content-between">
                    <div class="mr-auto my-auto">
                        <i class="fas fa-table me-1"></i>
                        {{ __('Hasil Klasifikasi KNN') }}
                    </div>

                    <div class="my-auto">
                        <button class="btn btn-sm btn-success text-nowrap" wire:click="klasifikasiKnn">
                            <i class="nav-icon fas fa-sync"></i>
                            Klasifikasi KNN
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
                    <livewire:admin.datatable.klasifikasi-k-n-n.data-hasil-table />
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
