<?php

namespace App\Policies;

use App\Models\Dokumen;
use App\Models\JenisDokumen;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DokumenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Dokumen $dokumen)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Menentukan apakah user dapat memperbarui dokumen.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(?User $user, Dokumen $dokumen)
    {
        return (auth()->check() && auth()->user()->id == $dokumen->user_id);
    }

    public function download(?User $user, Dokumen $dokumen)
    {
        $jenisDokumen = JenisDokumen::with('roles_can_download')->find($dokumen->jenis_dokumen_id);

        return in_array(auth()->user()->role_id, $jenisDokumen->roles_can_download->pluck('id')->toArray()) || (auth()->check() && auth()->user()->id == $dokumen->user_id);
    }

    /**
     * Menentukan apakah user dapat menghapus dokumen.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(?User $user, Dokumen $dokumen)
    {
        return auth()->user()->id == $dokumen->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Dokumen $dokumen)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dokumen  $dokumen
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Dokumen $dokumen)
    {
        //
    }
}
