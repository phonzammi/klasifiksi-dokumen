<?php

namespace App\Http\Livewire\Admin\Datatable;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class UsersTable extends DataTableComponent
{
    // protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setEagerLoadAllRelationsEnabled();
        // $this->setAdditionalSelects(['users.prodi']);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("NIM/NIP", "nim_nip")
                ->sortable(),
            Column::make("Nama Lengkap", "name")
                ->sortable()
                ->searchable(),
            Column::make("Alamat Email", "email")
                ->sortable()
                ->searchable(),
            Column::make("Prodi", "prodi.nama_prodi")
                ->sortable(
                    fn (Builder $query, string $direction) => $query->orderBy('prodi.nama_prodi', $direction)
                )
                ->searchable(),
            Column::make("Jurusan", "prodi.jurusan.nama_jurusan")
                ->sortable(
                    fn (Builder $query, string $direction) => $query->orderBy('jurusan.nama_jurusan', $direction)
                )
                ->searchable(),
            Column::make("Hak Akses (Jabatan)", 'role_id')
                // ->label(
                //     fn ($row, Column $column)  => $row->role_id == 1 ? '<span class="badge badge-primary">' . $row->role->role_name . '</span>' : '<span class="badge badge-success">' . $row->role->role_name . '</span>'
                // )
                // ->html()
                ->format(
                    fn ($value, $row, Column $column)  => '<span class="badge badge-success">' . $row->role->role_name . '</span>'
                )
                ->html()
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
                                "wire:click" => '$' . "emit('editUserModal', $row->id)",
                                'class' => 'btn btn-sm btn-info',
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn ($row) => 'Hapus')
                        ->location(fn ($row) => "#")
                        ->attributes(function ($row) {
                            return [
                                "wire:click" => '$' . "emit('deleteUserConfirmation', $row->id)",
                                'class' => 'btn btn-sm btn-danger',
                            ];
                        }),
                ]),
        ];
    }

    public function builder(): Builder
    {
        return User::query()
            ->where('is_admin', 0)
            ->with([
                'role',
                'prodi' => function ($query) {
                    $query->with('jurusan');
                }
            ]);
    }
}
