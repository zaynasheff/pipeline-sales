<div>
    <x-filament-actions::group
        icon="heroicon-o-ellipsis-vertical"
        size="md"
        color="black"
        :actions="[
            $this->deleteDealAction()
        ]"
    >

    </x-filament-actions::group>

    <x-filament-actions::modals />
</div>
