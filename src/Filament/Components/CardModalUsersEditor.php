<?php

namespace Zaynasheff\PipelineSales\Filament\Components;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class CardModalUsersEditor extends Component implements HasForms
{
    use InteractsWithForms;

    public Deal $deal;

    public bool $editingUsers = false;

    public array $data = [];

    public function mount(Deal $deal): void
    {
        $this->deal = $deal;

        $this->data['users'] = $deal->users()->pluck('id')->toArray();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('users')
                    ->label('')
                    ->multiple()
                    ->options(fn () => User::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray())
                    ->preload()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->updateUsers($state))
                    ->extraAttributes([
                        'x-on:blur' => 'setTimeout(() => { $wire.endEditingUsers() }, 50)',
                        'class' => 'w-full',
                    ]),

            ])
            ->statePath('data');
    }

    public function startEditingUsers(): void
    {
        $this->editingUsers = true;

        $this->data['users'] = $this->deal->users()->pluck('id')->toArray();
    }

    public function endEditingUsers(): void
    {
        $this->editingUsers = false;

        $this->dispatch('dealUpdated', uuid: $this->deal->uuid);
    }

    public function updateUsers($state): void
    {

        $this->deal->users()->sync($state);

        $this->dispatch('dealUpdated', uuid: $this->deal->uuid);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('pipeline-sales::components.card-modal-users-editor');
    }
}
