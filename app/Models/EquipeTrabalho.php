<?php

namespace App\Models;

use App\Enums\StatusInscricaoEquipeTrabalho;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EquipeTrabalho extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'equipe_trabalho';

    protected $fillable = [
        'nome',
        'avatar_url',
        'form_data',
        'status',
    ];

    protected $casts = [
        'form_data' => 'array',
        'avatar_url' => 'string',
        'status' => StatusInscricaoEquipeTrabalho::class,
    ];
}
