<div>
    <style>
        .card-heading{
            word-break: break-all;
        }
    </style>
    @if ($editingName)
        {{ $this->form }}
    @else
        <div
            class="card-heading text-xl font-bold cursor-pointer text-gray-900 dark:text-gray-100 hover:opacity-70 transition break-all"
            wire:click="startEditingName"
        >
            {{ $deal->name }}
        </div>
    @endif
</div>
