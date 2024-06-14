<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function plots()
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'id');
    }

    public function marketers()
    {
        return $this->belongsTo(Marketer::class, 'marketer_id', 'id');
    }
}
