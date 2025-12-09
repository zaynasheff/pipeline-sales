<?php

namespace Zaynasheff\PipelineSales\Filament\Components;


use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
    public function viewDealAction()
    {
        return Action::make('viewDeal')
//            ->label('')
//            ->button()
            ->modalHeading($this->deal->name)
            ->modalWidth('4xl')
            ->record($this->deal)
            ->modalSubmitAction(false) // если не нужна кнопка сохранить
            ->modalContent(view('pipeline-sales::components.kanban-card-modal', [
                'deal' => $this->deal,
            ]));
    }
    public function render()
    {
        return view('pipeline-sales::components.kanban-card');
    }
}
