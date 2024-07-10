<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imposto extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nome'
    ];
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'aliquota')->withPivot('aliquota');
    }
}
