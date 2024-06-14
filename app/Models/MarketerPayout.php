<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketerPayout extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function marketer() {
        return $this->belongsTo(Marketer::class, 'marketer_id' , 'id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'project_id' , 'id');
    }

    public function plot() {
        return $this->belongsTo(Plot::class, 'plot_id' , 'id');
    }

    public function Director()
    {
        return $this->belongsTo(Director::class, 'director_vcity_id', 'id');
    }

    public function assistantDirector()
    {
        return $this->belongsTo(Director::class, 'ad_vcity_id', 'id');
    }

    public function crm()
    {
        return $this->belongsTo(Director::class, 'crm_vcity_id', 'id');
    }

    public function chiefDirector()
    {
        return $this->belongsTo(Director::class, 'chief_vcity_id', 'id');
    }

    public function seniorDirector()
    {
        return $this->belongsTo(Director::class, 'senior_vcity_id', 'id');
    }

    // public function payment() {
    //     return $this->belongsTo(Payment::class, 'payment_id' , 'id');
    // }

}
