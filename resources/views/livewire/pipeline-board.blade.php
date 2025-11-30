<div
    class="flex gap-4"
>
    @foreach ($stages as $stage)
        <div class="w-1/4 bg-white shadow rounded p-4">
            <h3 class="font-bold text-lg mb-3">{{ $stage->name }}</h3>

            {{-- Sortable Kanban column --}}
            <div
                wire:sortable="updateDealOrder"
                wire:sortable-group="deals"
                wire:sortable.options='{"group":"deals"}'
                wire:sortable-group-id="{{ $stage->id }}"
                class="space-y-2 min-h-[200px]"
            >
                @foreach ($stage->deals as $deal)
                    <div
                        wire:sortable.item="{{ $deal->id }}"
                        wire:key="deal-{{ $deal->id }}"
                        class="bg-gray-100 p-3 rounded shadow cursor-move"
                    >
                        <div class="font-semibold">{{ $deal->title }}</div>
                        <div class="text-sm text-gray-600">{{ $deal->value }} $</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
