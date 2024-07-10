<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nome'
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
    public function impostos()
    {
        return $this->belongsToMany(Imposto::class, 'aliquota')->withPivot('aliquota');
    }
}
