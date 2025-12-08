<div id="kanban-board"
     class="flex gap-4 overflow-x-auto py-2 px-1  snap-x snap-mandatory"
     x-on:stage-created.window="setTimeout(() => { $wire.$refresh() }, 100);"
>
    @foreach($stages as $stage)
        <livewire:kanban-column
            :stage="$stage"
            :key="'stage-'.$stage->uuid"
        />
    @endforeach

    <div class="kanban-add-stage m-1" style="width: 350px;">
        {{$this->createStageAction}}
    </div>

            <x-filament-actions::modals />

</div>


