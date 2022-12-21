<?php

namespace App\Http\Livewire\Admin\Datatable;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Dokumen;
use App\Models\JenisDokumen;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class DocumentsTable extends DataTableComponent
{
    // protected $model = Dokumen::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // $this->setDefaultSort('id', 'asc');
        $this->setAdditionalSelects(['lampiran', 'jenis_dokumen']);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nama dokumen", "nama_dokumen")
                ->sortable(
                    fn (Builder $query, string $direction) => $query->orderBy('nama_dokumen', $direction)
                )
                ->searchable(),
            Column::make("Jenis Dokumen", "jenis_dokumen.jenis_dokumen")
                ->sortable(
                    fn (Builder $query, string $direction) => $query->orderBy('jenis_dokumen.jenis_dokumen', $direction)
                )
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => $row["jenis_dokumen.jenis_dokumen"] ? $row["jenis_dokumen.jenis_dokumen"] : '<span class="badge badge-danger">Tidak Tersedia</span>'
                )->html(),
            Column::make("Diunggah Oleh", "uploaded_by.name")
                ->sortable(
                    fn (Builder $query, string $direction) => $query->orderBy('users.name', $direction)
                )
                ->searchable(),

            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            ButtonGroupColumn::make('Aksi')
                ->attributes(function ($row) {
                    return [
                        'class' => 'mx-auto',
                    ];
                })
                ->buttons([
                    LinkColumn::make('Edit') // make() has no effect in this case but needs to be set anyway
                        ->title(fn ($row) => 'Edit')
                        ->location(fn ($row) => "#")
                        ->attributes(function ($row) {
                            return [
                                "wire:click" => '$' . "emit('editDocumentModal', $row->id)",
                                'class' => 'btn btn-sm btn-info',
                            ];
                        }),
                    LinkColumn::make('Aksi', 'lampiran_url')
                        ->title(fn ($row) => "Unduh")
                        ->location(fn ($row) => $row->lampiran_url)
                        ->attributes(fn ($row) => [
                            'class' => 'btn btn-sm btn-success',
                            'alt' => 'Lampiran ' . $row->nama_dokumen,
                        ]),
                    LinkColumn::make('Hapus')
                        ->title(fn ($row) => 'Hapus')
                        ->location(fn ($row) => "#")
                        ->attributes(function ($row) {
                            return [
                                "wire:click" => '$' . "emit('deleteDocumentConfirmation', $row->id)",
                                'class' => 'btn btn-sm btn-danger',
                            ];
                        }),
                ]),

        ];
    }

    public function filters(): array
    {
        return [
            MultiSelectFilter::make('Jenis Dokumen')
                ->options(
                    JenisDokumen::query()
                        ->with('roles')
                        ->orderBy('jenis_dokumen')
                        ->get()
                        ->keyBy('id')
                        ->map(fn ($jenis_dokumen) => $jenis_dokumen->jenis_dokumen)
                        ->toArray()
                )->filter(function (Builder $builder, array $values) {
                    $builder->whereHas('jenis_dokumen', fn ($query) => $query->whereIn('jenis_dokumen.id', $values));
                }),
        ];
    }


    public function builder(): Builder
    {
        return Dokumen::query()
            ->with([
                'jenis_dokumen' => function ($query) {
                    $query->with('roles');
                },
                'uploaded_by'
            ]);
    }
}
