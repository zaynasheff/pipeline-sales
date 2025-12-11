<?php

namespace Zaynasheff\PipelineSales\Filament\Components;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;
use Zaynasheff\PipelineSales\Models\Stage;

class KanbanColumn extends Component implements HasForms,HasActions
{
    use InteractsWithActions, InteractsWithForms;

    public Stage $stage;

    protected $listeners = [
        'dealMoved' => 'refreshStage',
    ];

    public function getName(): string
    {
        return 'kanban-column';
    }

    public function render()
    {
        return view('pipeline-sales::components.kanban-column');
    }

    public function editStageAction(): Action
    {
        return Action::make('editStage')
            ->label(__('pipeline-sales::pipeline-sales.edit'))
            ->modalHeading('Редактирование этапа')
            ->modalSubmitActionLabel(__('pipeline-sales::pipeline-sales.save'))
            ->modalWidth(MaxWidth::Large)
            ->icon('heroicon-o-pencil')
            ->form([
                TextInput::make('name')
                    ->label(__('pipeline-sales::pipeline-sales.stage_name'))
                    ->required()
                    ->default($this->stage->name)
            ])
            ->action(function ($data) {

                $this->stage->update($data);

                // Сообщаем родителю обновить список
                $this->dispatch('stageEdited',id:'kanban-board');
            });
    }

    public function deleteStageAction(): Action
    {
        return Action::make('deleteStage')
            ->label(__('pipeline-sales::pipeline-sales.delete'))
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function () {
                $this->stage->delete();

                // Сообщаем родителю обновить список
                $this->dispatch('stageDeleted',id:'kanban-board');
            });
    }

    public function createDealAction(): Action
    {
        return Action::make('createDeal')
            ->label(__('pipeline-sales::pipeline-sales.add_deal'))
            ->modalHeading(__('pipeline-sales::pipeline-sales.add_deal'))
            ->modalSubmitActionLabel(__('pipeline-sales::pipeline-sales.save'))
            ->modalWidth(MaxWidth::Large)
            ->icon('heroicon-o-plus')
            ->color('gray')
            ->extraAttributes(['class' => 'w-full'])
            ->form([
                TextInput::make('name')
                    ->label(__('pipeline-sales::pipeline-sales.deal_name'))
                    ->required()

            ])
            ->action(function ($data) {

                $lastDealPosition = $this->stage->deals->max('position');

                Deal::query()
                    ->create(
                        [
                            'name' => $data['name'],
                            'position' => (int)$lastDealPosition + 1,
                            'stage_uuid'=>$this->stage->uuid
                        ]);

                $this->stage->load('deals');
                $this->dispatch('dealCreated',id:'kanban-board');

            });
    }

    #[On('dealMoved')]
    public function refreshStage(): void
    {
        $this->stage->refresh();
    }
}
