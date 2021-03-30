<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advisorylevel extends Model
{
    use HasFactory;

    protected $table = 'advisorylevels';

    protected $primaryKey = 'id';

    protected $fillable = ['rainfallevent_id', 'intensityratio', 'duration'];

    public function rainfallevents()
    {
        return $this->belongsTo(Rainfallevent::class, 'rainfallevent_id');
    }
}
