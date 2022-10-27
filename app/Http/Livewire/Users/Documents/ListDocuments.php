<?php

namespace App\Http\Livewire\Users\Documents;

use App\Models\Dokumen;
use App\Models\JenisDokumen;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class ListDocuments extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $nama_dokumen, $jenis_dokumen_id, $dokumenModel;
    public $lampiran = null;
    public $openDocumentModal = false;
    public $isEditing = false;
    public $openDeleteDocumentConfirmation = false;

    protected $listeners = ['editDocumentModal', 'deleteDocumentConfirmation', 'closeModal' => 'closeDocumentModal'];

    public function resetInput()
    {
        $this->nama_dokumen = "";
        $this->jenis_dokumen_id = "";
        $this->lampiran = null;
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
            'lampiran' => [Rule::requiredIf(!$this->dokumenModel), 'nullable', 'mimes:pdf', 'max:2048'],
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
        if ($this->isEditing || $this->openDeleteDocumentConfirmation) {
            $this->reset();
        }
        $this->openDocumentModal = false;
        $this->isEditing = false;
        $this->openDeleteDocumentConfirmation = false;
    }

    public function store()
    {
        $validated = $this->validate([
            'nama_dokumen' => 'required|unique:dokumen,nama_dokumen',
            'jenis_dokumen_id' => 'required|numeric',
            'lampiran' => 'required|mimes:pdf|max:2048'
        ]);

        $jenis_dokumen = JenisDokumen::find($validated['jenis_dokumen_id']);

        $this->authorize('create', $jenis_dokumen);

        $nama_lampiran = Str::slug($validated['nama_dokumen']) . "." . $this->lampiran->extension();

        $dokumen_baru = new Dokumen();
        $dokumen_baru->nama_dokumen = $validated['nama_dokumen'];
        $dokumen_baru->jenis_dokumen_id = $jenis_dokumen->id;
        $dokumen_baru->user_id = auth()->user()->id;
        $dokumen_baru->lampiran = $nama_lampiran;

        if ($dokumen_baru->save()) {
            $this->lampiran->storeAs("lampiran/{$jenis_dokumen->jenis_dokumen}", $nama_lampiran, 'public');
        };

        session()->flash('message', "Dokumen Baru : '{$dokumen_baru->nama_dokumen}' Berhasil Di Upload !");
        // $this->resetInput();
        $this->emit('refreshDatatable');
        $this->reset();
    }

    public function editDocumentModal(Dokumen $dokumen)
    {
        $this->authorize('update', $dokumen);

        $this->openDocumentModal = true;
        $this->isEditing = true;

        $this->dokumenModel = $dokumen;
        $this->nama_dokumen = $this->dokumenModel->nama_dokumen;
        $this->jenis_dokumen_id = $this->dokumenModel->jenis_dokumen_id;
    }

    public function update()
    {
        $this->authorize('update', $this->dokumenModel);

        $validatedData = $this->validate([
            'nama_dokumen' => ['required', Rule::unique('dokumen')->ignore($this->dokumenModel)],
            'jenis_dokumen_id' => 'required|numeric',
            'lampiran' => [Rule::requiredIf(!$this->dokumenModel), 'nullable', 'mimes:pdf', 'max:2048'],
        ]);

        $jenis_dokumen_old = JenisDokumen::find($this->dokumenModel->jenis_dokumen_id);
        $jenis_dokumen_new = JenisDokumen::find($validatedData['jenis_dokumen_id']);

        if ($validatedData['jenis_dokumen_id'] != $this->dokumenModel->jenis_dokumen_id && !$this->lampiran) {
            Storage::disk("public")->move("lampiran/{$jenis_dokumen_old->jenis_dokumen}/{$this->dokumenModel->lampiran}", "lampiran/{$jenis_dokumen_new->jenis_dokumen}/{$this->dokumenModel->lampiran}");
        }

        $this->dokumenModel->nama_dokumen = $validatedData['nama_dokumen'];
        $this->dokumenModel->jenis_dokumen_id = $jenis_dokumen_new->id;

        if ($this->lampiran) {
            if ($this->lampiran != $this->dokumenModel->lampiran) {
                Storage::disk("public")->delete("lampiran/{$jenis_dokumen_old->jenis_dokumen}/{$this->dokumenModel->lampiran}");
            }
            $nama_lampiran = Str::slug($validatedData['nama_dokumen']) . "." . $this->lampiran->extension();
            $this->dokumenModel->lampiran = $nama_lampiran;
        }

        if ($this->dokumenModel->save()) {
            if ($this->lampiran) {
                $this->lampiran->storeAs("lampiran/{$jenis_dokumen_new->jenis_dokumen}", $nama_lampiran, 'public');
            }
        }

        session()->flash('message', "Dokumen {$this->dokumenModel->nama_dokumen} Berhasil Diperbarui !");
        $this->emit('refreshDatatable');
        $this->reset();
    }

    public function deleteDocumentConfirmation(Dokumen $dokumen)
    {
        $this->authorize('delete', $dokumen);

        $this->dokumenModel = $dokumen;
        $this->nama_dokumen = $this->dokumenModel->nama_dokumen;
        // $this->lampiran = $this->dokumenModel->lampiran;
        $this->openDeleteDocumentConfirmation = true;
    }

    public function delete()
    {
        $this->authorize('delete', $this->dokumenModel);

        $jenis_dokumen = JenisDokumen::find($this->dokumenModel->jenis_dokumen_id);

        if ($this->dokumenModel->delete()) {
            Storage::disk("public")->delete("lampiran/{$jenis_dokumen->jenis_dokumen}/{$this->dokumenModel->lampiran}");
        };
        session()->flash('message', "Dokumen {$this->dokumenModel->nama_dokumen} Berhasil Dihapus !");
        $this->emit('refreshDatatable');
        $this->reset();
    }

    public function render()
    {
        $semua_jenis_dokumen = JenisDokumen::whereHas('roles_can_upload', function ($query) {
            $query->where('role_id', auth()->user()->role_id);
        })->orderBy('jenis_dokumen')->get();
        return view('livewire.users.documents.list-documents', compact('semua_jenis_dokumen'))
            ->layout('layouts.users');
    }
}
