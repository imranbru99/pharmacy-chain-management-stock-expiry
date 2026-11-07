<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Concerns\ChecksModuleAccessPage;
use App\Modules\Ai\Services\AiAssistantService;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class AiAssistantPage extends Page implements HasForms
{
    use ChecksModuleAccessPage;
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'AI';

    protected static ?string $navigationLabel = 'AI Assistant';

    protected static ?string $title = 'AI Assistant';

    protected string $view = 'filament.pages.ai-assistant';

    public ?string $prompt = null;

    public string $lastResponse = '';

    protected static function moduleKey(): string
    {
        return 'ai';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('prompt')->required()->rows(6)->label('Prompt'),
        ]);
    }

    public function submit(AiAssistantService $ai): void
    {
        $state = $this->form->getState();
        $prompt = (string) ($state['prompt'] ?? '');

        if (trim($prompt) === '') {
            Notification::make()->title('Enter a prompt')->warning()->send();

            return;
        }

        $this->lastResponse = $ai->ask($prompt, 'filament_assistant', Auth::id());
        Notification::make()->title('Response received')->success()->send();
    }
}
