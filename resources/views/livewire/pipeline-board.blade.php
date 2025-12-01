<div class="space-y-4">

    @if(!$pipeline)
        <div class="p-4 bg-gray-50 rounded shadow text-center">
            <h2 class="text-lg font-medium">Нет Pipeline</h2>
            <div class="mt-2 flex justify-center space-x-2">
                <input type="text" wire:model.defer="newPipelineName" placeholder="Название Pipeline"
                       class="border rounded px-2 py-1" />
                <button wire:click="createPipeline"
                        class="bg-primary-600 text-white px-4 py-1 rounded hover:bg-primary-700">
                    Создать
                </button>
            </div>
            @error('newPipelineName') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>
    @else
        <h2 class="text-xl font-semibold">{{ $pipeline->name }}</h2>
        {{-- здесь рендер канбан --}}
        @foreach($stages as $stage)
            <div class="p-2 border rounded mb-2">
                <h3 class="font-medium">{{ $stage->name }}</h3>
                {{-- список сделок --}}
                @foreach($stage->deals as $deal)
                    <div class="p-1 border rounded mb-1">{{ $deal->name }}</div>
                @endforeach
            </div>
        @endforeach
    @endif

</div>
