<?php

namespace App\Http\Livewire\Admin\Datatable\KlasifikasiKNN;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\DataHasil;
use Illuminate\Database\Eloquent\Builder;

class DataHasilTable extends DataTableComponent
{
    // protected $model = DataHasil::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Nama Dokumen", "nama_dokumen")
                ->sortable(),
            Column::make("Jenis Dokumen", "jenis_dokumen.jenis_dokumen")
                ->sortable(
                    fn (Builder $query, string $direction) => $query->orderBy('jenis_dokumen.jenis_dokumen', $direction)
                )
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => $row["jenis_dokumen.jenis_dokumen"] ? $row["jenis_dokumen.jenis_dokumen"] : '<span class="badge badge-danger">Tidak Tersedia</span>'
                )->html(),
            Column::make("Nilai Kemiripan", "nilai_kemiripan"),
            Column::make("Mirip Dengan Dokumen", "data_training.nama_dokumen")
                ->format(
                    fn ($value, $row, Column $column) => $row["data_training.nama_dokumen"] ? $row["data_training.nama_dokumen"] : '<span class="badge badge-danger">Tidak Tersedia</span>'
                )->html(),
        ];
    }
    public function builder(): Builder
    {
        return DataHasil::query()->with(['jenis_dokumen', 'data_training']);
    }
}
