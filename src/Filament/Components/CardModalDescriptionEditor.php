<?php

namespace Zaynasheff\PipelineSales\Filament\Components;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class CardModalDescriptionEditor extends Component implements HasForms
{
    use InteractsWithForms;

    public Deal $deal;

    public bool $editingDescription = false;

    public array $data = [];

    public function mount(Deal $deal)
    {
        $this->deal = $deal;

        $this->form->fill([
            'description' => $deal->description,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                RichEditor::make('description')
                    ->label(false)
                    ->visible(fn () => $this->editingDescription)
                    ->disableToolbarButtons(['attachFiles']) // опционально
                    ->extraAttributes(['class' => 'min-h-[200px]'])
                    ->afterStateUpdated(fn ($state) => $this->saveDescription($state))
                    ->extraInputAttributes([
                        'x-on:blur' => '$wire.endEditingDescription()',
                    ]),
            ])
            ->statePath('data'); // важно!
    }

    public function startEditingDescription(): void
    {

        $this->editingDescription = true;

        $this->form->fill([
            'description' => $this->deal->description,
        ]);
    }

    public function saveDescription($value): void
    {
        $this->deal->update(['description' => $value]);

        $this->dispatch('dealUpdated', uuid: $this->deal->uuid);
    }

    public function endEditingDescription(): void
    {

        $this->editingDescription = false;
    }

    public function render()
    {
        return view('pipeline-sales::components.card-modal-description-editor');
    }
}
