<div class="space-y-4 border-t pt-3">

    {{-- Name --}}
    <livewire:card-modal-name-editor
        :deal="$deal"
        :key="'name-'.$deal->uuid"
    />

    {{--Tags--}}
    <livewire:card-modal-tags-editor
        :deal="$deal"
        :key="'tag-'.$deal->uuid"
    />
    {{--Description--}}
    <livewire:card-modal-description-editor
        :deal="$deal"
        :key="'desc-'.$deal->uuid"
    />


    {{--Users--}}
    <livewire:card-modal-users-editor
        :deal="$deal"
        :key="'user-'.$deal->uuid"
    />

    {{-- Amount --}}
{{--    <div class="text-gray-700 dark:text-gray-300">--}}
{{--        Сумма: <span class="font-semibold">{{ $deal->amount }} $</span>--}}
{{--    </div>--}}




{{--    --}}{{-- Client --}}
{{--    <div class="border-t pt-3">--}}
{{--        <h3 class="font-semibold mb-2">О клиенте</h3>--}}
{{--        <p>{{ $deal->client?->name }}</p>--}}
{{--    </div>--}}

</div>
