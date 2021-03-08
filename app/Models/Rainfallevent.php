<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rainfallevent extends Model
{
    use HasFactory;

    protected $table = 'rainfallevents';

    protected $primaryKey = 'id';

    public function rainfalldata_start()
    {
        return $this->hasOne(Rainfalldata::class, 'id', 'rainfalldata_id_start');
    }

    public function rainfalldata_end()
    {
        return $this->hasOne(Rainfalldata::class, 'id', 'rainfalldata_id_end');
    }

    public function raingauge()
    {
        return $this->belongsTo(Raingauge::class);
    }

}
