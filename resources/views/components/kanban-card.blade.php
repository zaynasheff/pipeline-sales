<div>
    <div class="shadow p-3 rounded-lg  bg-gray-50 dark:bg-gray-800 cursor-pointer drag-handle-card
     hover:bg-gray-100 dark:hover:bg-gray-100 transition duration-300"
         wire:click="mountAction('viewDeal')"
    >
        <div class="font-semibold">{{ $deal->name }}</div>
        <div class="text-sm text-gray-500">{{ $deal->amount }} $</div>
    </div>
    <x-filament-actions::modals />
</div>
