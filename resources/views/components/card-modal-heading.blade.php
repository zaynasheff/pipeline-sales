<style>
    /* Находим родительский h2 и заставляем его быть флексом на всю ширину */
    .fi-modal-heading {
        width: 100%;
        display: block;
    }
    /* Убираем возможные ограничения у обертки */
    .fi-modal-header div:has(> .fi-modal-heading) {
        width: 100%;
    }
</style>

<div class="flex items-center justify-between w-full" x-data >
    <h2 class="text-lg font-bold text-gray-950 dark:text-white">
        {{ $deal->stage->name }}
    </h2>

    <div class="flex items-center gap-x-2">
        <x-filament::dropdown placement="bottom-end">
            <x-slot name="trigger">
                <x-filament::icon-button
                    icon="heroicon-m-ellipsis-vertical"
                    color="black"
                    size="md"
                />
            </x-slot>

            <x-filament::dropdown.list>
                <x-filament::dropdown.list.item
                    icon="heroicon-o-archive-box"
                    color="gray"
                    tag="button"
                    x-on:click="$wire.triggerArchiveFromModal()"
                >
                    {{__('pipeline-sales::pipeline-sales.archive_deal')}}
                </x-filament::dropdown.list.item>
                <x-filament::dropdown.list.item
                    icon="heroicon-m-trash"
                    color="danger"
                    tag="button"
                    x-on:click="$wire.triggerDeleteFromModal()"
                >
                    {{__('pipeline-sales::pipeline-sales.delete_deal')}}
                </x-filament::dropdown.list.item>

            </x-filament::dropdown.list>
        </x-filament::dropdown>
    </div>
</div>
