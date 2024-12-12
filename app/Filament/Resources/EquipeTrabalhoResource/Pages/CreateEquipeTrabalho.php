<?php

namespace App\Filament\Resources\EquipeTrabalhoResource\Pages;

use App\Enums\StatusLacamento;
use App\Filament\Resources\EquipeTrabalhoResource;
use App\Filament\Resources\EquipeTrabalhoResource\EquipeTrabalhoForm;
use App\Models\Lancamento;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEquipeTrabalho extends CreateRecord
{
    protected static string $resource = EquipeTrabalhoResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                EquipeTrabalhoForm::getFormCreate(),
            );
    }

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
            'nome' => 'Equipe de trabalho - ' . $formData['nome'] . '  ' .$pai. ' ' . $mae,
            'descricao' => 'Inscricão campista: ' . $formData['nome'],
            'data' => Carbon::now(),
            'user_id' => $userAuth->id,
            'tipo' => 0,
            'forma_pagamento' => 4,
            'status' => StatusLacamento::Pendente->value,
            'valor' => (200*$count) *100 ,
        ]);
    }
}
