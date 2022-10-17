<?php

namespace App\Http\Livewire\Users\Datatable;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Dokumen;
use App\Models\JenisDokumen;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class UserDocumentsTable extends DataTableComponent
{
    // protected $model = Dokumen::class;
    protected $index = 0;
    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSingleSortingDisabled();
        $this->setDefaultSort('id', 'asc');
    }

    public function columns(): array
    {
        $this->index = $this->page > 1 ? ($this->page - 1) * $this->perPage : 0;
        return [
            // Column::make(__('No.'))->format(fn () => ++$this->index),
            Column::make("No.", "id")->format(fn () => ++$this->index)
                // ->sortable()
                ->setSortingPillTitle('Key')
                ->setSortingPillDirections('0-9', '9-0'),
            Column::make("Nama Dokumen", "nama_dokumen")
                ->sortable()
                ->searchable(),
            Column::make("Jenis Dokumen", "jenis_dokumen.jenis_dokumen")
                ->sortable()
                ->searchable(),
            Column::make("Diupload Oleh", "uploaded_by.name"),
            LinkColumn::make('Aksi', 'lampiran_url')
                ->title(fn ($row) => "Unduh")
                ->location(fn ($row) => $row->lampiran_url)
                ->attributes(fn ($row) => [
                    'class' => 'btn btn-sm btn-success',
                    'alt' => 'Lampiran ' . $row->nama_dokumen,
                ]),
            // ButtonGroupColumn::make('Aksi')
            //     ->attributes(function ($row) {
            //         return [
            //             'class' => 'mx-auto',
            //         ];
            //     })
            //     ->buttons([
            //         LinkColumn::make('Aksi', 'lampiran_url')
            //             ->title(fn ($row) => "Unduh")
            //             ->location(fn ($row) => $row->lampiran_url)
            //             ->attributes(fn ($row) => [
            //                 'class' => 'btn btn-sm btn-success',
            //                 'alt' => 'Lampiran ' . $row->nama_dokumen,
            //             ]),
            //     ]),
        ];
    }

    public function filters(): array
    {
        return [
            MultiSelectFilter::make('Jenis Dokumen')
                ->options(
                    JenisDokumen::query()
                        ->whereHas('roles', function ($query) {
                            $query->where('role_id', auth()->user()->role_id);
                        })
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
            ->whereHas('jenis_dokumen.roles', function ($query) {
                $query->where('role_jenis_dokumen.role_id', auth()->user()->role_id);
            })
            ->select('dokumen.id', 'nama_dokumen', 'jenis_dokumen.jenis_dokumen', 'users.name', 'dokumen.created_at', 'lampiran');
    }
}
