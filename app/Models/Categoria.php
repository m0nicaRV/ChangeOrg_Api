<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    use HasFactory;
    protected $fillable = ['nombre'];
    public function peticione(){
        return $this->hasMany(Peticione::class);
    }
}
