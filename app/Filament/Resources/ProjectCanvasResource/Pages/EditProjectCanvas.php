<?php

namespace App\Filament\Resources\ProjectCanvasResource\Pages;

use App\Enums\ProjectCanvasSection;
use App\Filament\Resources\ProjectCanvasResource;
use App\Models\ProjectCanvasItem;
use App\Enums\ProjectStatus;
use App\Enums\RiskImpact;
use App\Enums\RiskProbability;
use App\Enums\RiskStatus;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectRisk;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Support\Facades\DB;

class EditProjectCanvas extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ProjectCanvasResource::class;

    protected string $view = 'filament.resources.project-canvas-resource.pages.edit-project-canvas';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string | Htmlable
    {
        return 'Canvas: ' . $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('convert')
                ->label('Gerar Projeto Manual')
                ->color('success')
                ->icon('heroicon-o-rocket-launch')
                ->requiresConfirmation()
                ->action(function () {
                    $canvas = $this->record;

                    if ($canvas->project_id) {
                        Notification::make()
                            ->title('Este canvas já foi convertido.')
                            ->warning()
                            ->send();
                        return;
                    }

                    DB::transaction(function () use ($canvas) {
                        // 1. Agrupar itens
                        $items = $canvas->items->groupBy('section');

                        // Helper para pegar texto de uma seção
                        $getText = fn($section) => isset($items[$section->value]) 
                            ? $items[$section->value]->pluck('content', 'title')->map(fn($c, $t) => "• **$t**: $c")->join("\n")
                            : null;

                        // 2. Criar Projeto
                        $project = Project::create([
                            'name' => $canvas->name,
                            'description' => $canvas->description . "\n\n" . $this->buildDescriptionAppend($items),
                            'status' => ProjectStatus::NOT_STARTED, // Iniciado como não iniciado
                            'owner_id' => $canvas->owner_id ?? auth()->id(),
                            'company_id' => auth()->user()->company_id 
                                ?? Company::first()?->id, // Fallback para a primeira empresa do sistema
                            // Mapeamento Direto
                            'justification' => $getText(ProjectCanvasSection::JUSTIFICATION),
                            'objectives' => $getText(ProjectCanvasSection::SMART_OBJ),
                            'scope' => $getText(ProjectCanvasSection::PRODUCT) . "\n\n" . $getText(ProjectCanvasSection::REQUIREMENTS),
                            'assumptions' => $getText(ProjectCanvasSection::PREMISES),
                            'success_criteria' => $getText(ProjectCanvasSection::DELIVERABLES),
                        ]);

                        // 3. Criar Riscos
                        if (isset($items[ProjectCanvasSection::RISKS->value])) {
                            foreach ($items[ProjectCanvasSection::RISKS->value] as $riskItem) {
                                ProjectRisk::create([
                                    'project_id' => $project->id,
                                    'name' => $riskItem->title,
                                    'description' => $riskItem->content,
                                    'status' => RiskStatus::IDENTIFIED ?? 'identified',
                                    'probability' => RiskProbability::MEDIUM ?? 3, // Default
                                    'impact' => RiskImpact::MEDIUM ?? 3, // Default
                                    'owner_id' => $project->owner_id,
                                    'identified_at' => now(),
                                ]);
                            }
                        }

                        // 4. Atualizar Canvas
                        $canvas->update([
                            'project_id' => $project->id,
                            'status' => 'converted',
                        ]);

                        Notification::make()
                            ->title('Projeto criado com sucesso!')
                            ->success()
                            ->actions([
                                Action::make('view')
                                    ->label('Ver Projeto')
                                    ->url(route('filament.admin.resources.projects.edit', $project)),
                            ])
                            ->send();
                        
                    });
                }),
            Action::make('pdf')
                ->label('Exportar PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn () => route('project-canvas.pdf', $this->record))
                ->openUrlInNewTab(),
        ];
    }
    
    // Método chamado pelo botão "+" em cada seção do Canvas
    public function addItem(string $section)
    {
        $this->mountAction('createItem', ['section' => $section]);
    }

    public function editItem($itemId)
    {
        $this->mountAction('editItem', ['itemId' => $itemId]);
    }
    
    protected function getActions(): array
    {
        return [];
    }

    // Filament v5: Individual action methods with "Action" suffix
    public function createItemAction(): Action
    {
        return Action::make('createItem')
            ->label('Adicionar Post-it')
            ->modalWidth('md')
            ->form([
                TextInput::make('title')->required()->label('Título'),
                Textarea::make('content')->label('Detalhes')->rows(3),
                Select::make('color')
                    ->options([
                        'yellow' => 'Amarelo',
                        'green' => 'Verde',
                        'red' => 'Vermelho',
                        'blue' => 'Azul',
                    ])
                    ->default('yellow')
                    ->required(),
            ])
            ->action(function (array $arguments, array $data) {
                $this->record->items()->create([
                    'section' => $arguments['section'],
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'color' => $data['color'],
                ]);
            });
    }

    public function editItemAction(): Action
    {
        return Action::make('editItem')
            ->label('Editar Post-it')
            ->modalWidth('md')
            ->fillForm(fn (array $arguments) => ProjectCanvasItem::find($arguments['itemId'])?->toArray() ?? [])
            ->form([
                TextInput::make('title')->required()->label('Título'),
                Textarea::make('content')->label('Detalhes')->rows(3),
                Select::make('color')
                    ->options([
                        'yellow' => 'Amarelo',
                        'green' => 'Verde',
                        'red' => 'Vermelho',
                        'blue' => 'Azul',
                    ])
                    ->required(),
            ])
            ->action(function (array $arguments, array $data) {
                ProjectCanvasItem::find($arguments['itemId'])?->update([
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'color' => $data['color'],
                ]);
            })
            ->extraModalFooterActions(fn (array $arguments) => [
                Action::make('delete')
                    ->label('Excluir')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () use ($arguments) {
                        ProjectCanvasItem::find($arguments['itemId'])?->delete();
                        $this->replaceMountedAction(null);
                    })
            ]);
    }

    public function updateItemSection(int $itemId, string $newSection)
    {
        $item = ProjectCanvasItem::find($itemId);
        if ($item) {
            $item->update(['section' => $newSection]);
        }
    }

    // Helper para a view renderizar as seções
    public function getSections(): array
    {
        return ProjectCanvasSection::cases();
    }

    protected function buildDescriptionAppend($items): string
    {
        $append = [];
        
        $sectionsToAppend = [
            ProjectCanvasSection::PIT,
            ProjectCanvasSection::BENEFITS,
            ProjectCanvasSection::STAKEHOLDERS,
            ProjectCanvasSection::TEAM,
            ProjectCanvasSection::TIMELINE,
            ProjectCanvasSection::COSTS,
        ];

        foreach ($sectionsToAppend as $section) {
            if (isset($items[$section->value])) {
                $append[] = "### " . $section->getLabel();
                foreach ($items[$section->value] as $item) {
                    $append[] = "- **{$item->title}**: {$item->content}";
                }
                $append[] = "";
            }
        }

        return implode("\n", $append);
    }
}
