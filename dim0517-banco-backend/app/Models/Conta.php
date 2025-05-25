<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Conta extends Model
{
    use HasFactory;
    protected $table = 'contas';

    protected $fillable = [
        'conta',
        'saldo',
        'tipo',
        'pontos'
    ];

    // Cast saldo as decimal with 2 decimal places
    protected $casts = [
        'saldo' => 'decimal:2',
    ];
}