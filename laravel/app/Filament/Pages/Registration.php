<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Registration extends Register
{
    protected ?string $maxWidth = '2xl';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Contact')
                        ->schema([
                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                        ]),
                    Wizard\Step::make('Tennis Profile')
                        ->schema([
                            $this->getRatingSinglesFormComponent(),
                            $this->getRatingDoublesFormComponent(),
                        ]),
                    Wizard\Step::make('Password')
                        ->schema([
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                ])->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                    <x-filament::button
                        type="submit"
                        size="sm"
                        wire:submit="register"
                    >
                        Register
                    </x-filament::button>
                    BLADE))),
            ]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getRatingSinglesFormComponent(): Component
    {
        return TextInput::make('rating_singles')
            ->label('Rating Singles')
            ->numeric()
            ->required()
            ->step('0.0001')
            ->default('9.0000')
            ->helperText('Enter your rating for singles');
    }

    protected function getRatingDoublesFormComponent(): Component
    {
        return TextInput::make('rating_doubles')
            ->label('Rating Doubles')
            ->numeric()
            ->required()
            ->step('0.0001')
            ->default('9.0000')
            ->helperText('Enter your rating for doubles');
    }
}
