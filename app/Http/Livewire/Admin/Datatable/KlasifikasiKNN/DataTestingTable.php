<?php

namespace App\Http\Livewire\Admin\Datatable\KlasifikasiKNN;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\DataTesting;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class DataTestingTable extends DataTableComponent
{
    protected $model = DataTesting::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['data_testing.id']);
    }

    public function columns(): array
    {
        return [
            Column::make("Nama dokumen", "nama_dokumen")
                ->sortable(),

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
                                "wire:click" => '$' . "emit('editDataTestingModal', $row->id)",
                                'class' => 'text-primary fas fa-edit',
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn ($row) => '')
                        ->location(fn ($row) => "#")
                        ->attributes(function ($row) {
                            return [
                                "wire:click" => '$' . "emit('deleteDataTestingConfirmation', $row->id)",
                                'class' => 'text-danger fas fa-trash',
                            ];
                        }),
                ]),
        ];
    }
}
