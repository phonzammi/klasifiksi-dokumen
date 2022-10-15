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

                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fas fa-file-upload me-1"></i>
                            Upload Dokumen Baru
                        </a>
                    </div>

                    <div class="card-body">
                        <livewire:users.datatable.user-documents-table />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
