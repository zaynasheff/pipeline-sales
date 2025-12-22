<div>
    <style>
        .fi-badge{
            max-height: 24px;
        }
        .column-heading{
            word-break: break-all;
        }
    </style>
    <div  class="draggable flex-shrink-0 snap-start" data-uuid="{{ $stage->uuid }}">
    <div  style="width: 275px;" class="bg-gray-100 dark:bg-gray-950 rounded-xl shadow p-4 flex flex-col h-full">
        <div class="flex justify-between ps-1">
            <h3 class="column-heading flex gap-3 text-gray-700 dark:text-gray-200 mb-5 cursor-move drag-handle" >
                {{ $stage->name }}
                <x-filament::badge
                    color="gray"
                    class="text-xs shrink-0"
                >
                    {{ $stage->deals->count() }}
                </x-filament::badge>
            </h3>
            <x-filament-actions::group
                :actions="[
                        $this->editStageAction(),
                        $this->deleteStageAction(),
                    ]"
                label="Actions"
                icon="heroicon-m-ellipsis-vertical"
                color="black"
                size="md"
                dropdown-placement="bottom-start"
            />

        </div>


        <div
            class="deals-sortable flex flex-col gap-3"
            data-stage-uuid="{{ $stage->uuid }}"
            style="max-height: calc(100vh - 450px);overflow-y: auto;"
        >
            @foreach($stage->deals as $deal)

                    <div class="deal-item overflow-visible drag-handle-card p-1"
                         data-uuid="{{ $deal->uuid }}"

                    >
                        <livewire:kanban-card
                            :deal="$deal"
                            :key="'deal-'.$deal->uuid"
                        />
                    </div>

            @endforeach
        </div>

        <div class="mt-3 p-1">
            {{$this->createDealAction}}

        </div>
    </div>
</div>
    <x-filament-actions::modals />
</div>
