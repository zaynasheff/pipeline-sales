<?php

namespace Zaynasheff\PipelineSales;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Zaynasheff\PipelineSales\Filament\Pages\PipelineBoard;

class PipelineSalesPlugin implements Plugin
{
    protected ?string $navigationLabel = null;

    protected ?string $navigationGroup = null;

    protected ?string $navigationIcon = null;

    protected ?int $navigationSort = null;

    protected ?bool $navigationHidden = null;

    protected ?string $pageTitle = null;

    public function navigationLabel(string $label): static
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function navigationGroup(string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function navigationIcon(string $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function navigationSort(int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function hideNavigation(bool $hidden = true): static
    {
        $this->navigationHidden = $hidden;

        return $this;
    }

    public function title(string $title): static
    {
        $this->pageTitle = $title;

        return $this;
    }

    public function getId(): string
    {
        return 'pipeline-sales';
    }

    public function register(Panel $panel): void
    {

        $panel

            // Используйте discoverPages/Resources если хотите
            ->discoverPages(
                in: __DIR__ . '/Filament/Pages',
                for: 'Zaynasheff\\PipelineSales\\Filament\\Pages'
            );
        // ->resources([...])
        // ->widgets([...])
    }

    public function boot(Panel $panel): void
    {

        Livewire::component(
            'kanban-board',
            \Zaynasheff\PipelineSales\Filament\Components\KanbanBoard::class
        );

        Livewire::component(
            'kanban-column',
            \Zaynasheff\PipelineSales\Filament\Components\KanbanColumn::class
        );

        Livewire::component(
            'kanban-card',
            \Zaynasheff\PipelineSales\Filament\Components\KanbanCard::class
        );

        Livewire::component(
            'card-modal',
            \Zaynasheff\PipelineSales\Filament\Components\CardModal::class
        );

        Livewire::component(
            'card-modal-name-editor',
            \Zaynasheff\PipelineSales\Filament\Components\CardModalNameEditor::class
        );

        Livewire::component(
            'card-modal-tags-editor',
            \Zaynasheff\PipelineSales\Filament\Components\CardModalTagsEditor::class
        );

        Livewire::component(
            'card-modal-description-editor',
            \Zaynasheff\PipelineSales\Filament\Components\CardModalDescriptionEditor::class
        );

        Livewire::component(
            'card-modal-users-editor',
            \Zaynasheff\PipelineSales\Filament\Components\CardModalUsersEditor::class
        );
//        Livewire::component(
//            'card-modal-dropdown-actions',
//            \Zaynasheff\PipelineSales\Filament\Components\CardModalDropdownActions::class
//        );




        PipelineBoard::$pluginNavigationLabel = $this->navigationLabel;
        PipelineBoard::$pluginNavigationGroup = $this->navigationGroup;
        PipelineBoard::$pluginNavigationIcon = $this->navigationIcon;
        PipelineBoard::$pluginNavigationSort = $this->navigationSort;
        PipelineBoard::$pluginNavigationHidden = $this->navigationHidden;
        PipelineBoard::$pluginPageTitle = $this->pageTitle;
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
