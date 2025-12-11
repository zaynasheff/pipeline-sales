<div>
    @if ($editingName)
        {{ $this->form }}
    @else
        <div
            class="text-xl font-bold cursor-pointer text-gray-900 dark:text-gray-100 hover:opacity-70 transition"
            wire:click="startEditingName"
        >
            {{ $deal->name }}
        </div>
    @endif
</div>
