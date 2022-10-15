<?php

namespace App\Http\Livewire\Admin\Datatable;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class RolesTable extends DataTableComponent
{
    // protected $model = Role::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Hak Akses (Jabatan)", "role_name")
                ->sortable()
                ->searchable(),
            Column::make('Jumlah User')
                ->label(function ($row) {
                    return $row->anggota_count;
                }),
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
                                "wire:click" => '$' . "emit('editRoleModal', $row->id)",
                                'class' => 'btn btn-sm btn-info',
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn ($row) => 'Hapus')
                        ->location(fn ($row) => "#")
                        ->attributes(function ($row) {
                            return [
                                "wire:click" => '$' . "emit('deleteRoleConfirmation', $row->id)",
                                'class' => 'btn btn-sm btn-danger',
                            ];
                        }),
                ]),
        ];
    }

    public function builder(): Builder
    {
        return Role::query()
            ->withCount('anggota');
        // ->leftJoin('users', 'roles.id', '=', 'users.role_id')
        // ->selectRaw('count(users.id) as anggota_count')
        // ->select('roles.id', 'roles.role_name');
    }
}
