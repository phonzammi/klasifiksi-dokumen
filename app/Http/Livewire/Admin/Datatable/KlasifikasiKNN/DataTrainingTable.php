<?php

namespace App\Http\Livewire\Admin\Datatable\KlasifikasiKNN;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\DataTraining;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class DataTrainingTable extends DataTableComponent
{
    // protected $model = DataTraining::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['data_training.id']);
    }

    public function columns(): array
    {
        return [
            Column::make("Nama dokumen", "nama_dokumen")
                ->sortable(),
            Column::make("Jenis Dokumen", "jenis_dokumen.jenis_dokumen")
                ->sortable(
                    fn (Builder $query, string $direction) => $query->orderBy('jenis_dokumen.jenis_dokumen', $direction)
                )
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => $row["jenis_dokumen.jenis_dokumen"] ? $row["jenis_dokumen.jenis_dokumen"] : '<span class="badge badge-danger">Tidak Tersedia</span>'
                )->html(),
            ButtonGroupColumn::make('Aksi')
                ->attributes(function ($row) {
                    return [
                        'class' => 'mx-auto',
                    ];
                })
                ->buttons([
                    LinkColumn::make('Edit') // make() has no effect in this case but needs to be set anyway
                        ->title(fn ($row) => '')
                        ->location(fn ($row) => "#")
                        ->attributes(function ($row) {
                            return [
                                "wire:click" => '$' . "emit('editDataTrainingModal', $row->id)",
                                'class' => 'text-primary fas fa-edit',
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn ($row) => '')
                        ->location(fn ($row) => "#")
                        ->attributes(function ($row) {
                            return [
                                "wire:click" => '$' . "emit('deleteDataTrainingConfirmation', $row->id)",
                                'class' => 'text-danger fas fa-trash',
                            ];
                        }),
                ]),
        ];
    }
    public function builder(): Builder
    {
        return DataTraining::query()->with(['jenis_dokumen'])->orderBy('jenis_dokumen');
    }
}
