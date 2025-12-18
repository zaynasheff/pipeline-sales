<div>
    <style>
        .stage-card{
            border:1px solid transparent;
        }
        .stage-card:hover{
            border-color: rgb(var(--primary-500));;
        }
    </style>
    <div
        wire:click="mountAction('viewDeal')"
        class="stage-card  shadow p-3 rounded-lg transition duration-300 bg-white dark:bg-gray-800 cursor-pointer"
    >
        <div class="font-semibold">{{ $deal->name }}</div>

        @if(!empty($deal->tags))
            <div class="flex flex-wrap gap-1 mt-2">
                @foreach($deal->tags as $tag)
                    <span
                        style="font-size:10px; line-height:1;"
                        class="px-1.5 py-0.5  bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>
        @endif

        <div class="flex items-center text-xs text-gray-400 mt-3 gap-1">
            <x-heroicon-o-clock class="w-3 h-3 mr-1" />
            <span>{{ $deal->created_at->diffForHumans() }}</span>
        </div>

    </div>
    <x-filament-actions::modals />
</div>

