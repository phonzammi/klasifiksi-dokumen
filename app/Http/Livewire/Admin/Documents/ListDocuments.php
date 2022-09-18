<?php

namespace App\Http\Livewire\Admin\Documents;

use Livewire\Component;

class ListDocuments extends Component
{

    public $openDocumentModal = false;

    public function uploadDocumentModal()
    {
        $this->dispatchBrowserEvent('open-upload-document-modal');

        $this->openDocumentModal = true;
    }

    public function uploadDocument()
    {
        dd('here');
    }

    public function render()
    {
        return view('livewire.admin.documents.list-documents');
    }
}
