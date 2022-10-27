<div class="mx-auto">
    @can('update', $row)
        <a href="#" wire:click="$emit('editDocumentModal', {{ $row->id }})" class="btn btn-sm btn-info">Edit</a>
    @endcan
    @can('download', $row)
        <a href="{{ $row->lampiran_url }}" class="btn btn-sm btn-success" alt="Lampiran Flowchart">Unduh</a>
    @endcan
    @can('delete', $row)
        <a href="#" wire:click="$emit('deleteDocumentConfirmation', {{ $row->id }})"
            class="btn btn-sm btn-danger">Hapus</a>
    @endcan
</div>
