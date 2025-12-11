
<div class="pt-3" x-data @click.outside="$wire.endEditingUsers()">

    <h3 class="font-semibold mb-2">Участники</h3>

    <div class="flex flex-wrap gap-2 items-center">
        @if($editingUsers)
            {{ $this->form }}
        @else
            <div class="flex -space-x-2">

                @foreach($deal->users as $user)
                    @php

                        $avatar = $user->avatar_url
                            ?? ($user->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) : null);


                        $hash = crc32($user->email ?? $user->name);
                        $bgColor = substr(sprintf('%06X', $hash), 0, 6);


                        if (! $avatar) {
                            $avatar = 'https://ui-avatars.com/api/?name='
                                . urlencode($user->name ?? 'User')
                                . '&background=' . $bgColor
                                . '&color=fff'
                                . '&size=128';
                        }
                    @endphp

                    <x-filament::avatar
                        src="{{ $avatar }}"
                        alt="{{ $user->name }}"
                        size="md"
                    />
                @endforeach
            </div>
        @endif

            <x-filament::button
                size="xs"
                color="gray"
                wire:click="startEditingUsers"
            >
                <div class="flex items-center gap-0.5">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    <x-heroicon-o-minus class="w-4 h-4" />
                </div>
            </x-filament::button>

    </div>
</div>
