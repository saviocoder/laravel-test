<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aliquota extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'categoria_id',
        'imposto_id',
        'aliquota'
    ];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function imposto()
    {
        return $this->belongsTo(Imposto::class);
    }
}
