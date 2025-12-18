<?php

namespace Zaynasheff\PipelineSales\Filament\Components;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class CardModalNameEditor extends Component implements HasForms
{
    use InteractsWithForms;

    public Deal $deal;

    public bool $editingName = false;

    public array $data = [];

    public function mount(Deal $deal): void
    {
        $this->deal = $deal;

        $this->form->fill([
            'name' => $deal->name,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('')
                    ->reactive()
                    ->autofocus()
                    ->rules(['required', 'min:2'])
                    ->visible(fn () => $this->editingName)
                    ->afterStateUpdated(function ($state) {

                        $this->saveName($state);
                    })
                    ->extraInputAttributes([
                        'x-on:blur' => '$wire.endEditingName()',
                    ])
                    ->extraAttributes(['class' => 'w-full']),
            ])
            ->statePath('data');
    }

    public function startEditingName(): void
    {
        $this->editingName = true;

        $this->form->fill([
            'name' => $this->deal->name,
        ]);
    }

    public function endEditingName(): void
    {
        $this->editingName = false;
    }

    public function saveName($value): void
    {

        $this->validate(
            [
                'data.name' => 'required|min:2',
            ],
            [
                'data.name.required' => __('pipeline-sales::validation.deal_name_required'),
                'data.name.min' => __('pipeline-sales::validation.deal_name_min'),
            ]
        );

        $this->deal->update(['name' => $value]);

        $this->dispatch('dealUpdated', uuid: $this->deal->uuid);
    }

    public function render()
    {
        return view('pipeline-sales::components.card-modal-name-editor');
    }
}
