<x-app-layout>
    <x-slot name="header">
        <div class="col-sm-6">
            <h1 class="m-0">Starter Page</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Starter Page</li>
            </ol>
        </div><!-- /.col -->
    </x-slot>

    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <x-jet-welcome />
            </div>
        </div>
    </div>
</x-app-layout>
