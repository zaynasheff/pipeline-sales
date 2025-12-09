<x-filament::page>
    <x-filament::section
        style="height: calc(100vh - 180px);"
    >
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
        (function(){
            const stageSortables = new WeakMap();
            const dealSortables = new WeakMap();

            function safeDestroy(sortable, el){
                if(!sortable || !el || !el.isConnected) return;
                try { sortable.destroy(); } catch(e){}
            }

            function initStageSorting(){
                const board = document.getElementById('kanban-board');
                if(!board) return;

                safeDestroy(stageSortables.get(board), board);

                const sortable = new Sortable(board, {
                    animation: 150,
                    handle: '.drag-handle',
                    direction: 'horizontal',
                    onEnd(evt){
                        const stages = Array.from(board.querySelectorAll('.draggable'))
                            .map((el,index)=>({uuid:el.dataset.uuid, position:index}));
                        const comp = Livewire.find(board.closest('[wire\\:id]').getAttribute('wire:id'));
                        comp.call('updateStagePosition', stages);
                    }
                });

                stageSortables.set(board, sortable);
            }

            function initDealsSorting(){
                const board = document.getElementById('kanban-board');
                if(!board) return;

                document.querySelectorAll('.deals-sortable').forEach(column=>{
                    if(dealSortables.has(column)) return; // уже есть sortable

                    const sortable = new Sortable(column, {
                        group:'deals',
                        animation:150,
                        handle:'.drag-handle-card',
                        ghostClass:'opacity-50',
                        onEnd(evt){
                            evt.item.style.opacity=''; // сброс ghostClass
                            const stageUuid = evt.to.dataset.stageUuid;
                            const deals = Array.from(evt.to.querySelectorAll('.deal-item'))
                                .map((el,index)=>({uuid:el.dataset.uuid, position:index, stage_uuid:stageUuid}));
                            const comp = Livewire.find(board.closest('[wire\\:id]').getAttribute('wire:id'));
                            comp.call('updateDealsPosition', deals);
                        }
                    });

                    dealSortables.set(column, sortable);
                });
            }

            function initAll(){
                setTimeout(()=>{
                    initStageSorting();
                    initDealsSorting();
                }, 10);
            }

            // Livewire hook для инициализации после любого рендера
            document.addEventListener('livewire:initialized', ()=>{
                initAll();

                Livewire.hook('message.processed', ()=>{
                    initAll();
                });
            });

            // MutationObserver для динамически добавляемых колонок
            const board = document.getElementById('kanban-board');
            if(board){
                const observer = new MutationObserver(mutations=>{
                    mutations.forEach(m=>{
                        m.addedNodes.forEach(node=>{
                            if(node.nodeType!==1) return;

                            // ищем новые deals-sortable в добавленных узлах
                            const newColumns = node.matches('.deals-sortable')
                                ? [node]
                                : Array.from(node.querySelectorAll('.deals-sortable'));

                            newColumns.forEach(column=>{
                                if(dealSortables.has(column)) return;

                                const sortable = new Sortable(column, {
                                    group:'deals',
                                    animation:150,
                                    handle:'.drag-handle-card',
                                    ghostClass:'opacity-50',
                                    onEnd(evt){
                                        evt.item.style.opacity=''; // сброс ghostClass
                                        const stageUuid = evt.to.dataset.stageUuid;
                                        const deals = Array.from(evt.to.querySelectorAll('.deal-item'))
                                            .map((el,index)=>({uuid:el.dataset.uuid, position:index, stage_uuid:stageUuid}));
                                        const comp = Livewire.find(board.closest('[wire\\:id]').getAttribute('wire:id'));
                                        comp.call('updateDealsPosition', deals);
                                    }
                                });

                                dealSortables.set(column, sortable);
                            });
                        });
                    });
                });
                observer.observe(board, { childList:true, subtree:true });
            }
        })();
    </script>

@endpush





