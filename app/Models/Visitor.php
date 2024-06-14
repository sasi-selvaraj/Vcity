<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'gate_pass';

    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function plot() {
        return $this->belongsTo(Plot::class, 'plot_id', 'id');
    }
    public function director() {
        return $this->belongsTo(Director::class, 'director_id', 'id');
    }
    public function marketer() {
        return $this->belongsTo(Marketer::class, 'marketer_id', 'id');
    }
}
