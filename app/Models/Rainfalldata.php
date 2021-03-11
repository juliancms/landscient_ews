<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rainfalldata extends Model
{
    use HasFactory;

    protected $table = 'rainfalldatas';

    protected $primaryKey = 'id';

    protected $fillable = ['raingauge_id', 'demodb_id', 'dateTime', 'P1', 'P2', 'quality'];

    public function raingauge()
    {
        return this->belongsTo(RageAttge::class);
    }

    public function setDateTimeAttribute( $value ) {
        $this->attributes['dateTime'] = (new Carbon(Carbon::createFromFormat('d/m/Y H:i', $value)))->format('Y-m-d H:i');
    }

    public function setP1Attribute( $value ) {
          $this->attributes['P1'] = str_replace(',', '.', $value);
    }

    public function setP2Attribute( $value ) {
        $this->attributes['P2'] = str_replace(',', '.', $value);
    }

}
