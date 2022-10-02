<?php

namespace App\Http\Livewire\Admin\Documents;

use App\Models\Dokumen;
use App\Models\JenisDokumen;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ListDocuments extends Component
{
    use WithFileUploads;

    public $nama_dokumen, $jenis_dokumen_id, $lampiran, $dokumenModel;
    public $openDocumentModal = false;
    public $isEditing = false;

    protected $rules = [
        'nama_dokumen' => 'required|unique:dokumen,nama_dokumen',
        'jenis_dokumen_id' => 'required',
        'lampiran' => 'required|mimes:pdf|max:2048'
    ];

    protected $validationAttributes = [
        'nama_dokumen' => 'Nama Dokumen',
        'jenis_dokumen_id' => 'Jenis Dokumen',
        'lampiran' => 'Lampiran'
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function resetInput()
    {
        $this->nama_dokumen = "";
        $this->jenis_dokumen_id = "";
        $this->lampiran = "";
        $this->dokumenModel = "";
        $this->openDocumentModal = false;
        $this->isEditing = false;
    }

    public function createDocumentModal()
    {
        $this->openDocumentModal = true;
        $this->isEditing = false;
    }

    public function storeDocument()
    {
        $validatedData = $this->validate();

        $jenis_dokumen = JenisDokumen::find($validatedData['jenis_dokumen_id'])->jenis_dokumen;

        $nama_lampiran = Str::slug($validatedData['nama_dokumen']) . "." . $this->lampiran->extension();

        $dokumen_baru = new Dokumen();
        $dokumen_baru->nama_dokumen = Str::title($validatedData['nama_dokumen']);
        $dokumen_baru->jenis_dokumen_id = $validatedData['jenis_dokumen_id'];
        $dokumen_baru->user_id = auth()->user()->id;
        $dokumen_baru->lampiran = $nama_lampiran;

        if ($dokumen_baru->save()) {
            $this->lampiran->storeAs("lampiran/{$jenis_dokumen}", $nama_lampiran, 'public');
        }

        session()->flash('message', "Dokumen Baru Berhasil Di Upload !");
        $this->resetInput();
    }

    public function editDocumentModal(Dokumen $dokumen)
    {
        $this->openDocumentModal = true;
        $this->isEditing = true;

        $this->dokumenModel = $dokumen;
        $this->dokumen = $dokumen->nama_dokumen;
    }

    public function updateDocument()
    {
        $validatedData = $this->validate([
            'nama_dokumen' => 'required|unique:dokumen,nama_dokumen,' . $this->dokumenModel->id,
            'jenis_dokumen_id' => 'required',
            'lampiran' => 'required|mimes:pdf|max:2048'
        ]);

        $jenis_dokumen = JenisDokumen::find($validatedData['jenis_dokumen_id'])->jenis_dokumen;

        $nama_lampiran = Str::slug($validatedData['nama_dokumen']) . "." . $this->lampiran->extension();

        $this->dokumenModel->nama_dokumen = Str::title($validatedData['nama_dokumen']);
        $this->dokumenModel->jenis_dokumen_id = $validatedData['jenis_dokumen_id'];
        $this->dokumenModel->user_id = auth()->user()->id;
        $this->dokumenModel->lampiran = $nama_lampiran;

        if ($this->dokumenModel->save()) {
            $this->lampiran->storeAs("lampiran/{$jenis_dokumen}", $nama_lampiran, 'public');
        }

        session()->flash('message', "Dokumen {$this->dokumenModel->nama_dokumen} Berhasil Diperbarui !");
        $this->resetInput();
    }

    public function render()
    {
        $semua_jenis_dokumen = JenisDokumen::all();
        $semua_dokumen = Dokumen::with(['jenis_dokumen', 'uploaded_by'])->latest()->paginate();
        return view('livewire.admin.documents.list-documents', compact('semua_jenis_dokumen', 'semua_dokumen'));
    }
}
