<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\JenisDokumen;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $semua_dokumen_count = Dokumen::count();
        $semua_jenis_dokumen_count = JenisDokumen::count();
        $semua_user_count = User::count();
        $semua_hak_akses_count = Role::count();
        // dd($semua_dokumen_count);
        return view(
            'admin.dashboard',
            compact(
                "semua_dokumen_count",
                "semua_jenis_dokumen_count",
                "semua_user_count",
                "semua_hak_akses_count"
            )
        );
    }
}
