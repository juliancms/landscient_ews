<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studysite extends Model
{
    use HasFactory;

    protected $table = 'studysites';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'alpha', 'beta', 'duration_initial', 'duration_final'];

    public function raingauges()
    {
        return $this->hasMany(Raingauge::class);
    }
}
