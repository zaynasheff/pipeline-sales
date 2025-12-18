<?php

namespace Zaynasheff\PipelineSales\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Zaynasheff\PipelineSales\Models\Pipeline;
use Zaynasheff\PipelineSales\Models\Stage;

class PipelineBoard extends Page implements HasForms
{
    use InteractsWithForms;

    /**
     * @var mixed|string|null
     */
    public static ?string $pluginNavigationLabel = null;

    public static ?string $pluginNavigationGroup = null;

    public static ?string $pluginNavigationIcon = null;

    public static ?int $pluginNavigationSort = null;

    public static ?bool $pluginNavigationHidden = null;

    public static ?string $pluginPageTitle = null;

    protected static ?string $slug = 'pipeline-board';

    protected static string $view = 'pipeline-sales::filament.pages.pipeline-board';

    public static function getNavigationLabel(): string
    {
        return static::$pluginNavigationLabel ?? __('pipeline-sales::pipeline-sales.pipeline_board');
    }

    public static function getNavigationGroup(): ?string
    {
        return static::$pluginNavigationGroup;
    }

    public static function getNavigationIcon(): ?string
    {
        return static::$pluginNavigationIcon ?? 'heroicon-o-view-columns';
    }

    public static function getNavigationSort(): ?int
    {
        return static::$pluginNavigationSort;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return ! static::$pluginNavigationHidden;
    }

    public function getTitle(): string
    {
        return static::$pluginPageTitle ?? __('pipeline-sales::pipeline-sales.pipeline_board');
    }

    // --- UUID выбранной воронки ---
    public ?string $uuid = null;

    // --- Текущая воронка ---
    public ?Pipeline $pipeline = null;

    protected function queryString(): array
    {
        return [
            'uuid' => ['except' => null],
        ];
    }

    public function mount(): void
    {

        if (! $this->uuid) {
            $default = Pipeline::first();
            if ($default) {
                // НЕ возвращаем, а делаем Livewire redirect
                $this->redirect(static::getUrl(['uuid' => $default->uuid]));
            }
        }

        if ($this->uuid) {
            $this->pipeline = Pipeline::where('uuid', $this->uuid)->firstOrFail();
        }

        $this->form->fill();
    }

    // --- Форма выбора воронки ---
    protected function getFormSchema(): array
    {
        return [
            Select::make('uuid')
                ->label(__('pipeline-sales::pipeline-sales.select_pipeline'))
                ->options(Pipeline::pluck('name', 'uuid'))
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    // Перезагрузка страницы на выбранную воронку
                    return redirect()->to(static::getUrl(['uuid' => $state]));
                })
                ->default($this->uuid),
        ];
    }

    public function getPipelines(): Collection
    {
        return Pipeline::all();
    }

    // --- Получить стадии текущей воронки ---
    public function getCurrentStages(): \Illuminate\Database\Eloquent\Collection | Collection
    {
        if (! $this->pipeline) {
            return collect();
        }

        return $this->pipeline->stages()->orderBy('position')->with('deals')->get();
    }

    // --- Количество колонок ---
    public function getColumnCount(): int
    {
        return max(1, $this->getCurrentStages()->count());
    }

    // --- Действия: создание новой воронки ---
    protected function getActions(): array
    {
        return [
            Action::make('createPipeline')
                ->label(__('pipeline-sales::pipeline-sales.create_pipeline'))
                ->modalHeading(__('pipeline-sales::pipeline-sales.new_pipeline'))
                ->modalSubmitActionLabel(__('pipeline-sales::pipeline-sales.save'))
                ->form([
                    TextInput::make('name')
                        ->label(__('pipeline-sales::pipeline-sales.pipeline_name'))
                        ->required()
                        ->unique(),
                    TextInput::make('description')
                        ->label(__('pipeline-sales::pipeline-sales.pipeline_description'))
                        ->nullable(),
                    Repeater::make('stages')
                        ->label(__('pipeline-sales::pipeline-sales.stages'))
                        ->schema([
                            TextInput::make('stage_name')
                                ->label(__('pipeline-sales::pipeline-sales.stage_name'))
                                ->required(),
                        ])
                        ->columns(1)
                        ->addActionLabel(__('pipeline-sales::pipeline-sales.add_stage'))
                        ->required(),
                ])
                ->action(function (array $data) {
                    $pipeline = Pipeline::create([

                        'name' => $data['name'],
                        'description' => $data['description'] ?? null,
                    ]);

                    foreach ($data['stages'] as $index => $stage) {
                        Stage::create([

                            'pipeline_uuid' => $pipeline->uuid,
                            'name' => $stage['stage_name'],
                            'position' => $index,
                        ]);
                    }

                    // Редирект на новую воронку
                    return redirect()->to(static::getUrl(['uuid' => $pipeline->uuid]));
                }),
        ];
    }
}
