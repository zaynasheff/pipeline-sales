<?php

namespace Zaynasheff\PipelineSales\Filament\Components;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class KanbanCard extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Deal $deal;

    public function getName(): string
    {
        return 'kanban-card';
    }

    protected $listeners = ['dealUpdated' => 'refreshDeal'];

    public function refreshDeal(string $uuid): void
    {
        if ($uuid === $this->deal->uuid) {
            $this->deal->refresh();
        }
    }

    protected function getActions(): array
    {
        return [
            $this->deleteDealAction(),
        ];
    }

    public function viewDealAction(): Action
    {
        return Action::make('viewDeal')
            ->modalHeading(function () {
                return new \Illuminate\Support\HtmlString(
                    view('pipeline-sales::components.card-modal-heading', [
                        'deal' => $this->deal,
                        'deleteAction' => $this->getAction('deleteDeal'),
                    ])->render()
                );
            })

            ->modalCloseButton(false)
            ->modalWidth(MaxWidth::SevenExtraLarge)
            ->modalCancelAction(false)
            ->record($this->deal)
            ->modalSubmitAction(false)
            ->modalContent(
                view('pipeline-sales::components.card-modal', [
                    'deal' => $this->deal,
                ])
            );
    }

    public function deleteDealAction(): Action
    {
        return Action::make('deleteDeal')
            ->label(__('pipeline-sales::pipeline-sales.delete'))
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->record($this->deal)
            ->requiresConfirmation()
            ->modalHeading(__('pipeline-sales::pipeline-sales.delete_deal') . ': ' . $this->deal->name)
            ->action(function () {
                $this->deal->forceDelete();
                $this->dispatch('dealDeleted', id: 'kanban-column');
            });
    }

    public function archiveDealAction(): Action
    {
        return Action::make('archiveDeal')
            ->label(__('pipeline-sales::pipeline-sales.archive'))
            ->icon('heroicon-o-archive-box')
            ->color('warning')
            ->record($this->deal)
            ->requiresConfirmation()
            ->modalHeading(__('pipeline-sales::pipeline-sales.archive_deal') . ': ' . $this->deal->name)
            ->action(function () {
                $this->deal->delete();
                $this->dispatch('dealArchived', id: 'kanban-column');
            });
    }

    public function triggerDeleteFromModal(): void
    {

        $this->unmountAction();

        $this->mountAction('deleteDeal');
    }

    public function triggerArchiveFromModal(): void
    {

        $this->unmountAction();

        $this->mountAction('archiveDeal');
    }

    public function render()
    {
        return view('pipeline-sales::components.kanban-card');
    }
}
