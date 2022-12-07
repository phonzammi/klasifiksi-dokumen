<?php

namespace App\Http\Livewire\Admin\Documents;

use App\Models\Dokumen;
use App\Models\JenisDokumen;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ListDocuments extends Component
{
    use WithFileUploads;

    public $nama_dokumen, $jenis_dokumen_id, $lampiran, $dokumenModel;
    public $openDocumentModal = false;
    public $isEditing = false;
    public $openDeleteDocumentConfirmation = false;

    public $jenis_dokumen = NULL;

    protected $listeners = ['editDocumentModal', 'deleteDocumentConfirmation', 'closeModal' => 'closeDocumentModal'];

    // protected $rules = [
    //     'nama_dokumen' => 'required|unique:dokumen,nama_dokumen',
    //     'jenis_dokumen_id' => 'required|numeric',
    //     'lampiran' => 'required|mimes:pdf|max:2048'
    // ];

    protected $validationAttributes = [
        'nama_dokumen' => 'Nama Dokumen',
        'jenis_dokumen_id' => 'Jenis Dokumen',
        'lampiran' => 'Lampiran'
    ];

    public function updated($fields)
    {
        // $this->nama_dokumen = Str::title($this->nama_dokumen);

        $this->validateOnly($fields, [
            'nama_dokumen' => ['required', Rule::unique('dokumen')->ignore($this->dokumenModel)],
            'jenis_dokumen_id' => 'required|numeric',
            'lampiran' => [Rule::requiredIf(!$this->dokumenModel), 'mimes:pdf', 'max:2048'],
        ]);
    }

    public function resetInput()
    {
        $this->nama_dokumen = "";
        $this->jenis_dokumen_id = "";
        $this->lampiran = "";
        $this->dokumenModel = "";
        $this->openDocumentModal = false;
        $this->isEditing = false;
        $this->emit('refreshDatatable');
    }

    public function createDocumentModal()
    {
        $this->openDocumentModal = true;
        $this->isEditing = false;
    }

    public function storeDocument()
    {
        $validatedData = $this->validate([
            'nama_dokumen' => 'required|unique:dokumen,nama_dokumen',
            'jenis_dokumen_id' => 'required|numeric',
            'lampiran' => 'required|mimes:pdf|max:2048'
        ]);

        // $jenis_dokumen = JenisDokumen::find($validatedData['jenis_dokumen_id']);

        $nama_lampiran = Str::slug($validatedData['nama_dokumen']) . "." . $this->lampiran->extension();

        $dokumen_baru = new Dokumen();
        $dokumen_baru->nama_dokumen = $validatedData['nama_dokumen'];
        $dokumen_baru->jenis_dokumen_id = $this->jenis_dokumen->id;
        $dokumen_baru->user_id = auth()->user()->id;
        $dokumen_baru->lampiran = $nama_lampiran;

        if ($dokumen_baru->save()) {
            $this->lampiran->storeAs("lampiran/{$this->jenis_dokumen->jenis_dokumen}", $nama_lampiran, 'public');
        }

        session()->flash('message', "Dokumen Baru : '{$dokumen_baru->nama_dokumen}' Berhasil Di Upload !");
        $this->resetInput();
    }

    public function editDocumentModal(Dokumen $dokumen)
    {
        $this->openDocumentModal = true;
        $this->isEditing = true;

        $this->dokumenModel = $dokumen;
        $this->nama_dokumen = $this->dokumenModel->nama_dokumen;
        $this->jenis_dokumen_id = $this->dokumenModel->jenis_dokumen_id;
        $this->updatedNamaDokumen($this->nama_dokumen);
    }

    public function updateDocument()
    {
        $validatedData = $this->validate([
            'nama_dokumen' => 'required|unique:dokumen,nama_dokumen,' . $this->dokumenModel->id,
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

        // $this->dokumenModel->user_id = auth()->user()->id;

        if ($this->dokumenModel->save()) {
            if ($this->lampiran) {
                $this->lampiran->storeAs("lampiran/{$jenis_dokumen_new->jenis_dokumen}", $nama_lampiran, 'public');
            }
        }

        session()->flash('message', "Dokumen {$this->dokumenModel->nama_dokumen} Berhasil Diperbarui !");
        $this->emit('refreshDatatable');
        $this->reset();
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

    public function deleteDocumentConfirmation(Dokumen $dokumen)
    {
        $this->dokumenModel = $dokumen;
        $this->nama_dokumen = $this->dokumenModel->nama_dokumen;
        // $this->lampiran = $this->dokumenModel->lampiran;
        $this->openDeleteDocumentConfirmation = true;
    }

    public function deleteDocument()
    {
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
        $semua_jenis_dokumen = JenisDokumen::all();
        $semua_dokumen = Dokumen::with(['jenis_dokumen', 'uploaded_by'])->latest()->paginate(5);
        return view('livewire.admin.documents.list-documents', compact('semua_jenis_dokumen', 'semua_dokumen'));
    }

    public function updatedNamaDokumen($nama_dokumen)
    {
        $this->nama_dokumen = Str::title($this->nama_dokumen);
        $search = explode(" ", $this->nama_dokumen);

        if (!array_key_exists(1, $search)) {
            $this->jenis_dokumen = JenisDokumen::where('jenis_dokumen', 'like', '%' . $search[0] . '%')->first();
        } else {
            $this->jenis_dokumen = NULL;
        }


        if (!$this->jenis_dokumen && array_key_exists(1, $search)) {
            $this->jenis_dokumen = JenisDokumen::where('jenis_dokumen', 'like', "%{$search[0]} {$search[1]}%")->first();
        }

        $this->jenis_dokumen_id = $this->jenis_dokumen ? $this->jenis_dokumen->id : "";
        if ($nama_dokumen == "") {
            $this->jenis_dokumen = NULL;
            $this->jenis_dokumen_id = "";
        }
    }
}
