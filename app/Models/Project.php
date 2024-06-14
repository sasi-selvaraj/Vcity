<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function plots()
    {
        return $this->hasMany(Plot::class, 'project_id', 'id');
    }

    public function projectImage()
    {
        return $this->hasMany(ProjectImage::class, 'project_id', 'id');
    }
}
