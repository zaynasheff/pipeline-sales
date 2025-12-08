<x-filament::page>
    <x-filament::section>
            <div class="flex items-center mb-5">
                @if($this->getPipelines()->count())
                    {{ $this->form }}
                @endif
            </div>

            {{-- No pipelines message --}}
            @if(!$this->getPipelines()->count())
                <x-filament::section class="text-center py-10 text-gray-500">
                    {{ __('pipeline-sales::pipeline-sales.no_pipelines') }}
                </x-filament::section>
            @else

            <livewire:kanban-board
            :pipeline="$pipeline"
            :key="$pipeline->uuid" />


        @endif

    </x-filament::section>
</x-filament::page>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>


        function waitForBoard() {
            const board = document.getElementById('kanban-board');

            if (!board) {
                setTimeout(waitForBoard, 50);
                return;
            }

            if (board.dataset.sortableInitialized) return;
            board.dataset.sortableInitialized = true;

            new Sortable(board, {
                animation: 150,
                handle: '.drag-handle',
                direction: 'horizontal',
                onEnd: function(evt) {
                    const stages = Array.from(board.querySelectorAll('.draggable'))
                        .map((el, index) => ({
                            uuid: el.dataset.uuid,
                            position: index
                        }));


                   const component = Livewire.find(board.closest('[wire\\:id]').getAttribute('wire:id'));
                   component.call('updateStagePosition', stages);
                }
            });
        }

        function initDealsSorting() {
            const board = document.getElementById('kanban-board');
            const columns = document.querySelectorAll('.deals-sortable');

            columns.forEach(column => {
                if (column.dataset.sortableInitialized) return;
                column.dataset.sortableInitialized = true;

                new Sortable(column, {
                    group: 'deals', // позволяет перенос между колонками
                    animation: 150,
                    handle: '.drag-handle-card',
                    ghostClass: 'opacity-50',

                    onEnd: function(evt) {
                        const newColumn = evt.to;      // колонка, куда упал элемент
                        const stageUuid = newColumn.dataset.stageUuid;

                        // собрать порядок сделок внутри новой колонки
                        const deals = Array.from(newColumn.querySelectorAll('.deal-item'))
                            .map((el, index) => ({
                                uuid: el.dataset.uuid,
                                position: index,
                                stage_uuid: stageUuid // <-- новая колонка!
                            }));

                        console.log('updated deals:', deals);

                        const component = Livewire.find(
                            board.closest('[wire\\:id]').getAttribute('wire:id')
                        );

                        component.call('updateDealsPosition', deals);
                    }
                });

            });
        }


        waitForBoard();
        initDealsSorting();
    </script>
@endpush





