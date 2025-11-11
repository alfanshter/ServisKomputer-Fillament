<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class SelectUserWithModal extends Field
{
    protected string $view = 'filament.forms.components.select-user-with-modal';

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function ($component, $state) {
            // Load existing user data if available
            if ($state) {
                $component->state($state);
            }
        });
    }
}
