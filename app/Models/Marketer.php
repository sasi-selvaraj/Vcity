<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // public function marketer_attachments()
    // {
    //     return $this->hasMany(MarketerAttachment::class, 'marketer_id', 'id');
    // }

    public function Director()
    {
        return $this->belongsTo(Marketer::class, 'director_vcity_id', 'id');
    }

    public function assistantDirector()
    {
        return $this->belongsTo(Marketer::class, 'ad_vcity_id', 'id');
    }

    public function CRM()
    {
        return $this->belongsTo(Marketer::class, 'crm_vcity_id', 'id');
    }

    public function chiefDirector()
    {
        return $this->belongsTo(Marketer::class, 'chief_vcity_id', 'id');
    }

    public function seniorDirector()
    {
        return $this->belongsTo(Marketer::class, 'senior_vcity_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'marketer_id', 'id');
    }

}
