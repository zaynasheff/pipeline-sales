<div class="space-y-4 border-t pt-3">

    <div class="flex items-center gap-3">
        {{-- Name  --}}
        <div class="flex-1 min-w-0">
            <livewire:card-modal-name-editor
                :deal="$deal"
                :key="'name-'.$deal->uuid"
            />
        </div>
    </div>


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

</div>
