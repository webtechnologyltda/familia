<?php

namespace App\Filament\Resources\EquipeTrabalhoResource;

use App\Enums\StatusInscricaoEquipeTrabalho;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Leandrocfe\FilamentPtbrFormFields\Cep;

abstract class EquipeTrabalhoForm
{
    public static function getFormCreate(): array
    {
        return [
            ...self::getFormEquipeTrabalho()
        ];
    }

    public static function getFormUpdate(): array
    {
        return [
            ...self::getFormInformacaoInscricao(),
            ...self::getFormEquipeTrabalho()
        ];
    }

    public static function getFormEquipeTrabalho(): array
    {
        return [
            Section::make('Dados de Inscrição')
                ->icon('phosphor-user-list-fill')
                ->columns([
                    'default' => 1,
                    'lg' => 5,
                ])
                ->schema([
                    Grid::make()
                        ->columns([
                            'default' => 1,
                            'sm' => 1,
                            'md' => 3,
                            'lg' => 3,
                            'xl' => 3,
                        ])
                        ->schema([
                            Placeholder::make('offset')
                                ->hiddenLabel()
                                ->columns([
                                    'default' => 0,
                                    'sm' => 0,
                                    'md' => 1,
                                    'lg' => 1,
                                    'xl' => 1,
                                ]),
                            FileUpload::make('avatar_url')
                                ->hiddenLabel()
                                ->label('Foto de identificação')
                                ->optimize('webp')
                                ->placeholder(fn() => new HtmlString('<span><a class="text-primary-600 font-bold">Clique aqui</a></br>Para adicionar uma foto sua</span>'))
                                ->resize(15)
                                ->alignCenter()
                                ->imageEditor()
                                ->directory('foto-formulario')
                                ->columnSpan(1)
                                ->image()
                                ->imagePreviewHeight('400')
                                ->loadingIndicatorPosition('center')
                                ->panelAspectRatio('1:1')
                                ->removeUploadedFileButtonPosition('top-center')
                                ->uploadProgressIndicatorPosition('center')
                                ->imageEditorMode(2)
                                ->imageCropAspectRatio('1:1')
                                ->orientImagesFromExif(false)
                                ->extraAttributes(['rounded'])
                                ->imagePreviewHeight('250')
                                ->imageEditorAspectRatios([
                                    '1:1',
                                ])
                                ->panelLayout('integrated')
                                ->uploadingMessage('Uploading attachment...')
                                ->imageEditorEmptyFillColor('#000000')
                                ->required(),
                        ]),
                    Placeholder::make('mensagem_foto')
                        ->hiddenLabel()
                        ->hintColor(Color::Yellow)
                        ->hintIcon('heroicon-o-exclamation-circle')
                        ->columnSpanFull()
                        ->hint('Por favor, envie uma foto SEM óculos escuros ou acessórios que possam dificultar a sua identificação.'),
                    Grid::make()
                        ->label( 'Informações Pessoais' )
                        ->columns([
                            'default' => 1,
                            'sm' => 1,
                            'md' => 3,
                            'xl' => 2,
                        ])
                        ->schema([
                            TextInput::make('nome')
                                ->label('Sobrenome da  família')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'xl' => 2,
                                ])
                                ->maxLength(255),
                            TextInput::make('form_data.telefone_contato')->required()->mask( '(99) 9 9999-9999')->columnSpan(1)->label('Telefone de contato'),
                            TextInput::make('form_data.telefone_contato1')->mask( '(99) 9 9999-9999')->columnSpan(1)->label('Telefone de contato'),

                        ]),
                    Grid::make()
                        ->columnSpanFull()
                        ->schema([
                            Repeater::make('form_data.familiares')
                                ->schema([
                                    TextInput::make('nome')->required()->columnSpanFull(),
                                    TextInput::make('Idade')->numeric()->required(),
                                    Select::make('parentesco')
                                        ->label('Parentesco')
                                        ->options([
                                            'Pai' => 'Pai',
                                            'Mãe' => 'Mãe',
                                            'Filhos' => 'Filhos',
                                            'Avos' => 'Avos',

                                        ])
                                        ->required(),
                                    Radio::make('tamanho_camiseta')
                                        ->label('Tamanho da camiseta')
                                        ->options([
                                            '2' => '2',
                                            '4' => '4',
                                            '6' => '6',
                                            '8' => '8',
                                            '10' => '10',
                                            '12' => '12',
                                            '14' => '14',
                                            'PP' => 'PP',
                                            'P' => 'P',
                                            'M' => 'M',
                                            'G' => 'G',
                                            'GG' => 'GG',
                                            'EG' => 'EG',
                                            'X1' => 'X1',
                                            'O' => 'Outros',
                                        ])
                                        ->inline()
                                        ->inlineLabel(false)
                                        ->live()
                                        ->required()->columnSpanFull(),

                                    TextInput::make('tamanho_camiseta_outro')
                                        ->label('Tamanho da camiseta:')

                                        ->required()
                                        ->visible(fn(Get $get) => $get('tamanho_camiseta') == 'O')
                                        ->requiredIf('tamanho_camiseta', fn(Get $get) => $get('tamanho_camiseta') == 'O')
                                        ->minLength(1)
                                        ->maxLength(3),

                                    Radio::make('toma_remedio')
                                        ->label('Toma algum Remédio?')
                                        ->live()
                                        ->columnSpanFull()
                                        ->required()
                                        ->inline()
                                        ->inlineLabel(false)
                                        ->options([
                                            true => 'Sim',
                                            false => 'Não',
                                        ]),

                                    Textarea::make('remedio')
                                        ->rows(3)
                                        ->required(fn(Get $get) => $get('toma_remedio') == true)
                                        ->visible(fn(Get $get) => $get('toma_remedio') == true)
                                        ->label('Por favor, descreva os medicamentos abaixo e os horários de administração caso se aplique')
                                        ->columnSpanFull(),

                                    Radio::make('tem_recomendacao')
                                        ->label('Tem alguma recomendação especial?')
                                        ->live()
                                        ->columnSpanFull()
                                        ->required()
                                        ->inline()
                                        ->inlineLabel(false)
                                        ->options([
                                            true => 'Sim',
                                            false => 'Não',
                                        ]),

                                    Textarea::make('recomendacao')
                                        ->label('Qual?')
                                        ->required(fn(Get $get) => $get('tem_recomendacao') == true)
                                        ->visible(fn(Get $get) => $get('tem_recomendacao') == true)
                                        ->rows(3)
                                        ->columnSpanFull(),

                                    Radio::make('ja_participou_retiro')
                                        ->label('Já participou de algum acampamento/retiro ?')
                                        ->live()
                                        ->columnSpanFull()
                                        ->required()
                                        ->inline()
                                        ->inlineLabel(false)
                                        ->options([
                                            true => 'Sim',
                                            false => 'Não',
                                        ]),



                                ])
                                ->columnSpanFull()
                                ->columns(2)
                                ->minItems(1)
                                ->addActionLabel( 'Adicionar novo familiar' ),
                            Grid::make('Endereço')
                                ->columns([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 3,
                                    'xl' => 5,
                                ])
                                ->schema([

                                    Placeholder::make('info_endereco')
                                        ->hint('Informe o CEP para preencher os campos de endereço automaticamente. Clique na lupa para localizar o endereço.')
                                        ->hintColor(Color::Yellow)
                                        ->hintIcon('heroicon-o-exclamation-circle')
                                        ->hiddenLabel()
                                        ->columnSpanFull(),

                                    Cep::make('form_data.cep')
                                        ->label('CEP')
                                        ->required()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 3,
                                            'xl' => 2,
                                        ])
                                        ->viaCep(
                                        // Determines whether the action should be appended to (suffix) or prepended to (prefix) the cep field, or not included at all (none).
                                            mode: 'suffix',

                                            // Error message to display if the CEP is invalid.
                                            errorMessage: 'CEP inválido.',

                                            /**
                                             * Other form fields that can be filled by ViaCep.
                                             * The key is the name of the Filament input, and the value is the ViaCep attribute that corresponds to it.
                                             * More information: https://viacep.com.br/
                                             */
                                            setFields: [
                                                'form_data.rua' => 'logradouro',
                                                'form_data.numero' => 'numero',
                                                'form_data.ponto_referencia' => 'complemento',
                                                'form_data.bairro' => 'bairro',
                                                'form_data.cidade' => 'localidade',
                                                'form_data.estado' => 'uf'
                                            ],
                                        ),

                                    Hidden::make('breakLineEndereco')->columnSpan(1),

                                    TextInput::make('form_data.rua')
                                        ->required()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 3,
                                            'xl' => 4,
                                        ])
                                        ->label('Rua'),

                                    TextInput::make('form_data.numero')
                                        ->required()
                                        ->numeric()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 3,
                                            'xl' => 1,
                                        ])
                                        ->label('Número'),
                                    TextInput::make('form_data.ponto_referencia')
                                        ->label('Ponto Referência')
                                        ->minValue(0)
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 3,
                                            'xl' => 2,
                                        ]),

                                    TextInput::make('form_data.bairro')
                                        ->required()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 3,
                                            'xl' => 3,
                                        ])
                                        ->label('Bairro'),

                                    TextInput::make('form_data.cidade')
                                        ->required()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 4,
                                            'xl' => 3,
                                        ])
                                        ->readOnly()
                                        ->label('Cidade'),

                                    TextInput::make('form_data.estado')
                                        ->required()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 1,
                                            'xl' => 2,
                                        ])
                                        ->readOnly()
                                        ->label('Estado'),

                                ]),


                        ]),

                ])

        ];
    }

    public static function getFormFoto(): array
    {
        return [

            FileUpload::make('avatar_url')
                ->hiddenLabel()
                ->label('Foto de identificação')
                ->optimize('webp')
                ->placeholder(fn() => new HtmlString('<span><a class="text-primary-600 font-bold">Clique aqui</a></br>Para adicionar uma foto sua</span>'))
                ->resize(15)
                ->alignCenter()
                ->imageEditor()
                ->directory('foto-formulario-equipe-trabalho')
                ->columnStart([
                    'default' => 1,
                    'lg' => 3,
                ])
                ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/webp'])
                ->imagePreviewHeight('400')
                ->loadingIndicatorPosition('center')
                ->panelAspectRatio('1:1')
                ->removeUploadedFileButtonPosition('top-center')
                ->uploadProgressIndicatorPosition('center')
                ->imageEditorMode(2)
                ->imageCropAspectRatio('1:1')
                ->orientImagesFromExif(false)
                ->extraAttributes(['rounded'])
                ->imagePreviewHeight('250')
                ->imageEditorAspectRatios([
                    '1:1',
                ])
                ->panelLayout('integrated')
                ->uploadingMessage('Uploading attachment...')
                ->imageEditorEmptyFillColor('#000000')
                ->required(),

            Actions::make([
                FormAction::make('star')
                    ->icon('heroicon-m-eye')
                    ->label('Visualizar foto')
                    ->requiresConfirmation()
                    ->visible(fn(string $operation, array $data) => $operation !== 'create')
                    ->url(fn(Model $record) => Storage::url($record->avatar_url), shouldOpenInNewTab: true),
            ])
                ->visibleOn(['edit', 'view'])
                ->alignCenter()
                ->columnSpanFull(),
        ];
    }

    public static function getFormInformacaoInscricao(): array
    {
        return [
            Section::make('Informação da Inscrição')
                ->icon('heroicon-o-information-circle')
                ->columns([
                    'sm' => 2,
                ])
                ->columnSpan([
                    'default' => 1,
                    'lg' => 2
                ])
                ->schema([
                    Select::make('status')
                        ->columnSpan([
                            'default' => 1,
                        ])
                        ->searchable()
                        ->label('Status da Inscrição')
                        ->live()
                        ->preload()
                        ->afterStateUpdated(fn(Set $set) => $set('dia_pagamento', Carbon::now()->format('Y-m-d')))
                        ->options(StatusInscricaoEquipeTrabalho::class),

                    Textarea::make('observacoes')
                        ->label('Observações')
                        ->rows(3)
                        ->placeholder('Observações sobre a inscricão.')
                        ->columnSpan([
                            'default' => 1,
                        ]),
                ]),
        ];
    }
}
