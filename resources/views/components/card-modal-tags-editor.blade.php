<div class="pt-3" x-data
     @click.outside="$wire.endEditingTags()" >
    <h3 class="font-semibold mb-2">Теги</h3>

    @if ($editingTags)
        {{ $this->form }}
    @else
        <div class="flex flex-wrap gap-2 items-center">

            @foreach($deal->tags ?? [] as $tag)
                <span class="px-2 py-1 bg-gray-200 dark:bg-gray-800 text-sm rounded">
                    {{ $tag }}
                </span>
            @endforeach


                <x-filament::button
                    size="xs"
                    color="gray"
                    wire:click="startEditingTags"
                >
                    <div class="flex items-center gap-0.5">
                        <x-heroicon-o-plus class="w-4 h-4" />
                        <x-heroicon-o-minus class="w-4 h-4" />
                    </div>
                </x-filament::button>
        </div>
    @endif
</div>
