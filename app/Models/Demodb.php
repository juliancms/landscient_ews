<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demodb extends Model
{
    use HasFactory;

    protected $table = 'demodbs';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    public function rainfalldatas()
    {
        return $this->hasMany(Rainfalldata::class);
    }

    public function rainfallevents()
    {
        return $this->hasMany(Rainfallevent::class);
    }

}
