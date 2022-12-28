<div>
    <x-slot name="header">
        <div class="col-sm-6">
            <h1 class="m-0">Klasifikasi KNN</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Seluruh Dokumen</a></li>
                <li class="breadcrumb-item active">Klasifikasi KNN</li>
            </ol>
        </div><!-- /.col -->
    </x-slot>

    <div class="container-fluid">
        <div class="row">
            @livewire('admin.documents.klasifikasi-k-n-n.data-training')
            @livewire('admin.documents.klasifikasi-k-n-n.data-testing')
        </div>
    </div>

    {{-- <div class="container-fluid"> --}}
    {{-- </div> --}}

    @livewire('admin.documents.klasifikasi-k-n-n.data-hasil')

</div>
