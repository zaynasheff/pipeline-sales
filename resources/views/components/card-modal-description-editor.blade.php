<div class="pt-3"
     x-data
     @click.outside="$wire.endEditingDescription()"
>
    <h3 class="font-semibold mb-2">{{__('pipeline-sales::pipeline-sales.description')}}</h3>

    @if($editingDescription)
        {{ $this->form }}
    @else
        <div
            class="prose prose-sm dark:prose-invert cursor-pointer"
            wire:click="startEditingDescription"
        >
            {!! $deal->description ?: '<span class="text-gray-500">'. __('pipeline-sales::pipeline-sales.add_description').'</span>' !!}
        </div>
    @endif
</div>
