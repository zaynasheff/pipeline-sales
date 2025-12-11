<?php namespace Zaynasheff\PipelineSales\Filament\Components;


use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;
use Zaynasheff\PipelineSales\Models\Pipeline;
use Zaynasheff\PipelineSales\Models\Stage;

class KanbanBoard extends Component implements HasForms,HasActions
{
    use InteractsWithActions, InteractsWithForms;


    protected $listeners = [
        'stageDeleted' => 'reloadStages',
        'dealCreated' => 'reloadStages',
    ];


    public function getName(): string
    {
        return 'kanban-board';
    }

    public Pipeline $pipeline;

    public function createStageAction(): Action
    {
        return Action::make('createStage')
            ->label(__('pipeline-sales::pipeline-sales.add_stage'))
            ->modalHeading(__('pipeline-sales::pipeline-sales.add_stage'))
            ->modalSubmitActionLabel(__('pipeline-sales::pipeline-sales.save'))
            ->modalWidth(MaxWidth::Large)
            ->icon('heroicon-o-plus')
            ->color('gray')
            ->extraAttributes(['class' => 'w-full','style'=>'width: 275px;'])
            ->form([
                TextInput::make('name')
                    ->label(__('pipeline-sales::pipeline-sales.stage_name'))
                    ->required()

            ])
            ->action(function ($data) {

                $lastStagePosition = $this->pipeline->stages()->max('position');

                Stage::query()
                    ->create(
                        [
                            'name' => $data['name'],
                            'pipeline_uuid' => $this->pipeline->uuid,
                            'position' => (int)$lastStagePosition + 1
                        ]);


                $this->pipeline->load('stages.deals');


            });
    }

    public function updateStagePosition(array $stages): void
    {
        foreach ($stages as $stageData) {
            $stage = Stage::query()->where('uuid', $stageData['uuid'])->first();
            if ($stage) {
                $stage->update(['position' => $stageData['position']]);
            }
        }
        $this->pipeline->load('stages.deals');
    }

    public function updateDealsPosition(array $deals): void
    {

        foreach ($deals as $deal) {
            Deal::query()->where('uuid', $deal['uuid'])
                ->update([
                    'position' => $deal['position'],
                    'stage_uuid' => $deal['stage_uuid'],
                ]);
        }

        // перезагружаем доску
        $this->pipeline->load('stages.deals');
        $this->dispatch('dealMoved',id:'kanban-column');

    }

    #[On('stageDeleted')]
    public function reloadStages(): void
    {

       $this->pipeline->load('stages.deals');

    }


    public function render(): \Illuminate\Contracts\View\View
    {
        return view('pipeline-sales::components.kanban-board',
            ['stages' => $this->pipeline->stages()->orderBy('position')->with('deals')->get()]
        );
    }
}
