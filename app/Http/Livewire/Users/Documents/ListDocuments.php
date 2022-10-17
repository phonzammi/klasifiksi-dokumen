<?php

namespace App\Http\Livewire\Users\Documents;

use App\Models\Dokumen;
use App\Models\JenisDokumen;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class ListDocuments extends Component
{
    use WithFileUploads;

    public $nama_dokumen, $jenis_dokumen_id, $nama_lampiran, $dokumenModel;
    public $lampiran = null;
    public $openDocumentModal = false;
    public $isEditing = false;
    public $openDeleteDocumentConfirmation = false;

    public function resetInput()
    {
        $this->nama_dokumen = "";
        $this->jenis_dokumen_id = "";
        $this->lampiran = null;
        $this->nama_lampiran = "";
        $this->dokumenModel = "";
        $this->closeDocumentModal();
        $this->emit('refreshDatatable');
    }

    public function updated($fields)
    {
        $this->nama_dokumen = Str::title($this->nama_dokumen);

        $this->validateOnly($fields, [
            'nama_dokumen' => ['required', Rule::unique('dokumen')->ignore($this->dokumenModel)],
            'jenis_dokumen_id' => 'required|numeric',
            'lampiran' => 'required|mimes:pdf|max:2048'
        ]);
    }

    protected $validationAttributes = [
        'nama_dokumen' => 'Nama Dokumen',
        'jenis_dokumen_id' => 'Jenis Dokumen',
        'lampiran' => 'Lampiran'
    ];

    public function createDocumentModal()
    {
        $this->openDocumentModal = true;
        $this->isEditing = false;
    }

    public function closeDocumentModal()
    {
        if ($this->isEditing) {
            $this->nama_dokumen = "";
            $this->jenis_dokumen_id = "";
        }
        $this->openDocumentModal = false;
        $this->isEditing = false;
        $this->openDeleteDocumentConfirmation = false;
    }

    public function store()
    {
        $validated = $this->validate([
            'nama_dokumen' => ['required', Rule::unique('dokumen')->ignore($this->dokumenModel)],
            'jenis_dokumen_id' => 'required|numeric',
            'lampiran' => 'required|mimes:pdf|max:2048'
        ]);

        $this->nama_lampiran = Str::slug($this->nama_dokumen) . "." . $this->lampiran->extension();

        $jenis_dokumen = JenisDokumen::find($validated['jenis_dokumen_id']);

        $dokumen_baru = new Dokumen();
        $dokumen_baru->nama_dokumen = $this->nama_dokumen;
        $dokumen_baru->jenis_dokumen_id = $jenis_dokumen->id;
        $dokumen_baru->user_id = auth()->user()->id;
        $dokumen_baru->lampiran = $this->nama_lampiran;

        if ($dokumen_baru->save()) {
            $this->lampiran->storeAs("lampiran/{$jenis_dokumen->jenis_dokumen}", $this->nama_lampiran, 'public');
        };

        session()->flash('message', "Dokumen Baru : '{$dokumen_baru->nama_dokumen}' Berhasil Di Upload !");
        $this->resetInput();
    }

    public function render()
    {
        $semua_jenis_dokumen = JenisDokumen::whereHas('roles', function ($query) {
            $query->where('role_id', auth()->user()->role_id);
        })->orderBy('jenis_dokumen')->get();
        return view('livewire.users.documents.list-documents', compact('semua_jenis_dokumen'))
            ->layout('layouts.users');
    }
}
