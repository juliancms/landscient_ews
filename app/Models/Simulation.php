<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    use HasFactory;

    protected $table = 'simulations';

    protected $primaryKey = 'id';

    protected $fillable = ['raingauge_id', 'demodb_id'];

    public function raingauges()
    {
        return $this->belongsTo(Raingauge::class, 'raingauge_id');
    }

    public function demodbs()
    {
        return $this->belongsTo(Demodb::class, 'demodb_id');
    }

    public function rainfallevents()
    {
        return $this->hasMany(Rainfallevent::class);
    }

    public function advisorylevels()
    {
        return $this->rainfallevents()->with('advisorylevels');
    }
}
