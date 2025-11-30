<?php

namespace Zaynasheff\PipelineSales\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Zaynasheff\PipelineSales\Models\Pipeline;
use Zaynasheff\PipelineSales\Models\Stage;
use Zaynasheff\PipelineSales\Models\Deal;

class PipelineBoard extends Component
{
    public $pipeline;
    public $stages = [];

    /**
     * Mounts the Kanban board for the first pipeline.
     * Later we can allow user selection.
     */
    public function mount()
    {
        // Load the first pipeline (or a selected one)
        $this->pipeline = Pipeline::first();

        // Load stages with related deals
        $this->stages = Stage::where('pipeline_id', $this->pipeline->id)
            ->with('deals')
            ->orderBy('position')
            ->get();
    }

    /**
     * Update deal stage and position when dragging.
     *
     * @param int $dealId
     * @param int $newStageId
     * @param array $orderedIds
     */
    #[On('dealMoved')]
    public function updateDealOrder($dealId, $newStageId, $orderedIds)
    {
        // Move deal to a new stage
        $deal = Deal::find($dealId);
        $deal->stage_id = $newStageId;
        $deal->save();

        // Update order inside the column
        foreach ($orderedIds as $position => $id) {
            Deal::where('id', $id)->update(['position' => $position]);
        }

        $this->mount(); // reload data
    }

    public function render()
    {
        return view('pipeline-sales::livewire.pipeline-board');
    }
}
