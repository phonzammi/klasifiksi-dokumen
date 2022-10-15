<?php

namespace App\Http\Livewire\Users\Documents;

use App\Models\Dokumen;
use Livewire\Component;

class ListDocuments extends Component
{
    public function render()
    {
        return view('livewire.users.documents.list-documents')
            ->layout('layouts.users');
    }
}
