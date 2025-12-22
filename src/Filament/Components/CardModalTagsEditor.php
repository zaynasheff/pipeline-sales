<?php

namespace Zaynasheff\PipelineSales\Filament\Components;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class CardModalTagsEditor extends Component implements HasForms
{
    use InteractsWithForms;

    public Deal $deal;

    public bool $editingTags = false;

    public array $data = [];

    public function mount(Deal $deal): void
    {
        $this->deal = $deal;

        $this->form->fill([
            'tags' => $this->deal->tags ?? [],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TagsInput::make('tags')
                    ->visible(fn () => $this->editingTags)
                    ->label(false)
                    // ->suggestions($this->getAllTags())
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        $this->saveTags($state);
                    })
                    ->extraInputAttributes([
                        'data-prevent-focus-trap' => true,
                        'x-on:blur' => 'setTimeout(() => $wire.endEditingTags(), 150)',
                    ]),
            ])
            ->statePath('data');
    }

    public function startEditingTags(): void
    {
        $this->editingTags = true;

        $this->form->fill([
            'tags' => $this->deal->tags ?? [],
        ]);
    }

    public function endEditingTags(): void
    {

        if ($this->getErrorBag()->has('data.tags')) {
            return;
        }
        $this->saveTags($this->data['tags'] ?? []);
        $this->editingTags = false;
    }

    public function saveTags($value): void
    {
        $this->deal->update([
            'tags' => $value,
        ]);

        $this->dispatch('dealUpdated', uuid: $this->deal->uuid);
    }

    public function getAllTags(): array
    {
        return Deal::query()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('pipeline-sales::components.card-modal-tags-editor');
    }
}
