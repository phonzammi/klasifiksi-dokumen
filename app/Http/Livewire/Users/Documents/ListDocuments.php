<?php

namespace App\Http\Livewire\Users\Documents;

use App\Models\Dokumen;
use Livewire\Component;

class ListDocuments extends Component
{
    public function render()
    {
        return view('livewire.users.documents.list-documents', [
            "seluruh_dokumen" => Dokumen::with('jenis_dokumen')->whereHas('jenis_dokumen.roles', function ($query) {
                $query->where('role_jenis_dokumen.role_id', auth()->user()->role_id);
            })->get()
        ])
            ->layout('layouts.users');
    }
}
