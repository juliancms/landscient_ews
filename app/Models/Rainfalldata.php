<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rainfalldata extends Model
{
    use HasFactory;

    protected $table = 'rainfalldatas';

    protected $primaryKey = 'id';

    public function raingauge()
    {
        return this->belongsTo(Raingauge::class);
    }
}
