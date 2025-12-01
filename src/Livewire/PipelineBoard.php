<?php

namespace Zaynasheff\PipelineSales\Livewire;

use Livewire\Component;
use Zaynasheff\PipelineSales\Models\Deal;
use Zaynasheff\PipelineSales\Models\Pipeline;
use Zaynasheff\PipelineSales\Models\Stage;
use Livewire\Attributes\On;

class PipelineBoard extends Component
{
    public $pipeline = null;
    public $stages = [];

    // новое поле для формы
    public $newPipelineName = '';

    public function mount()
    {
        $this->loadPipeline();
    }

    private function loadPipeline()
    {
        $this->pipeline = Pipeline::first();

        if (! $this->pipeline) {
            $this->stages = [];
            return;
        }

        $this->stages = Stage::where('pipeline_id', $this->pipeline->id)
            ->with('deals')
            ->orderBy('position')
            ->get();
    }

    #[On('dealMoved')]
    public function updateDealOrder($dealId, $newStageId, $orderedIds)
    {
        if (! $this->pipeline) return;

        $deal = Deal::find($dealId);
        if ($deal) {
            $deal->stage_id = $newStageId;
            $deal->save();
        }

        foreach ($orderedIds as $position => $id) {
            Deal::where('id', $id)->update(['position' => $position]);
        }

        $this->loadPipeline();
    }

    public function createPipeline()
    {
        $this->validate([
            'newPipelineName' => 'required|string|max:255',
        ]);

        $pipeline = Pipeline::create([
            'name' => $this->newPipelineName,
        ]);

        // опционально можно создать первый Stage автоматически
        Stage::create([
            'name' => 'Stage 1',
            'pipeline_id' => $pipeline->id,
            'position' => 0,
        ]);

        $this->newPipelineName = '';

        $this->loadPipeline(); // загружаем новый pipeline
    }

    public function render()
    {
        return view('pipeline-sales::livewire.pipeline-board', [
            'pipeline' => $this->pipeline,
            'stages'   => $this->stages,
        ]);
    }
}
