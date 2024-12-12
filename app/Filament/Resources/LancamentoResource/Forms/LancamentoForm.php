<?php

namespace App\Filament\Resources\LancamentoResource\Forms;

use App\Enums\FormaPagamento;
use App\Enums\StatusLacamento;
use App\Enums\TipoLacamento;
use Carbon\Carbon;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Support\RawJs;
use Leandrocfe\FilamentPtbrFormFields\Money;

abstract class LancamentoForm
{
    public static function getFormSchema(): array
    {
        return [
          Grid::make([
              'default' => 1,
              'lg' => 3,
          ])
              ->schema([
               Section::make('Lançamento')
                   ->description('Controle financeiro do acampamento')
                   ->columns([
                       'sm' => 2,
                   ])
                   ->columnSpan([
                       'default' => 1,
                       'lg' => 2
                   ])
                   ->schema([

                       TextInput::make('nome')
                           ->label('Nome')
                           ->required(),

                       Money::make('valor')
                           ->label('Valor')
                           ->columns(1)
                           ->intFormat()
                           ->prefix(RawJs::make('R$'))
                           ->required(),

                       ToggleButtons::make('tipo')
                           ->label('Tipo de Lançamento')
                           ->options(TipoLacamento::class)
                           ->inline()
                           ->required(),

                       DatePicker::make('data')
                           ->label('Data de Lançamento')
                           ->required()
                           ->format('Y-m-d')
                           ->displayFormat('d/m/Y')
                           ->default(Carbon::now()->format('Y-m-d')),



                       Select::make('status')
                           ->label('Status')
                           ->options(StatusLacamento::class)
                           ->searchable()
                           ->required(),

                       Select::make('forma_pagamento')
                           ->label('Forma de Pagamento')
                           ->options(FormaPagamento::class)
                           ->searchable()
                           ->required(),

                       TextInput::make('comprador')
                           ->label('Comprador'),

                       Textarea::make('descricao')
                           ->label('Descricão'),
                   ]),

                  Section::make('comprovante')
                      ->label('Comprovantes')
                      ->icon('heroicon-o-document-text')
                      ->description('Anexe os comprovantes de pagamento.')
                      ->columnSpan(1)
                      ->schema([
                          TextInput::make('form_data.comprovante_nome')
                              ->label('Nome Comprovante'),
                          FileUpload::make('comprovante')
                              ->placeholder( 'Tamanho max.: 2MB')
                              ->hint('Tamanho máximo: 2MB')
                              ->label('Documento')
                              ->downloadable()
                              ->openable()
                              ->multiple()
                              ->maxSize(2048)
                              ->uploadingMessage('Carregando...')
                              ->acceptedFileTypes(['application/pdf', 'image/*'])
                              ->previewable(true)
                              ->columnSpan(2),
                      ]),



              ]),
        ];
    }
}
