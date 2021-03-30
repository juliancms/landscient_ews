<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raingauge extends Model
{
    use HasFactory;

    protected $table = 'raingauges';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'studysite_id'];

    public function studysites()
    {
        return $this->belongsTo(Studysite::class, 'studysite_id');
    }

    public function rainfalldatas()
    {
        return $this->hasMany(Rainfalldata::class);
    }

    public function rainfallevents()
    {
        return $this->hasMany(Rainfallevent::class);
    }
}