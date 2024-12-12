<?php

namespace App\Filament\Resources\CampistaResource\Pages;

use App\Enums\StatusLacamento;
use App\Enums\TipoLacamento;
use App\Filament\Resources\CampistaResource;

use App\Livewire\CampistaForm;
use App\Models\Lancamento;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateCampista extends CreateRecord
{

    protected static string $resource = CampistaResource::class;



    protected function afterCreate(): void
    {
        $userAuth = auth()->user();
        $recipient = User::whereNot('id', $userAuth->id)->get();
        $formData = $this->data;
        $familiares =$formData[ 'form_data' ]['familiares'];
        $mae = "";
        $pai ="";
        $count = count($familiares);

        foreach ($familiares as $familiar) {
            if ($familiar['parentesco'] == 'Mae') {
                $mae = $familiar['nome'];

            }
            if ($familiar['parentesco'] == 'Pai') {
                $pai = $familiar['nome'];
            }
        }

        Lancamento::create( [
            'nome' => 'Campista - ' . $formData['nome'] . ' - ' .$pai. ' ' . $mae,
            'descricao' => 'Inscricão campista: ' . $formData['nome'],
            'data' => Carbon::now(),
            'user_id' => $userAuth->id,
            'tipo' => 0,
            'forma_pagamento' => 4,
            'status' => StatusLacamento::Pendente->value,
            'valor' => (200*$count) *100 ,
        ]);

        Notification::make()
            ->info()
            ->title('Nova inscrição')
            ->body('Uma nova inscrição foi criada pelo usuário: ' . $userAuth->name . ', acesse as inscrições para mais detalhes.')
            ->sendToDatabase($recipient);
    }
}
