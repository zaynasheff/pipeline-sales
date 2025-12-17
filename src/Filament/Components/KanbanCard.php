<?php

namespace Zaynasheff\PipelineSales\Filament\Components;


use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;

class KanbanCard extends Component implements HasActions,HasForms
{
    use InteractsWithActions,InteractsWithForms;

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
            ->action(function () {
                $this->deal->delete();
                $this->dispatch('dealDeleted',id:'kanban-column');
            });
    }

    // Добавьте этот метод в класс KanbanCard
    public function triggerDeleteFromModal(): void
    {
        // Мы закрываем текущее окно просмотра, чтобы избежать конфликтов модалок
        $this->unmountAction();

        // И сразу монтируем действие удаления
        $this->mountAction('deleteDeal');
    }

    public function render()
    {
        return view('pipeline-sales::components.kanban-card');
    }
}
