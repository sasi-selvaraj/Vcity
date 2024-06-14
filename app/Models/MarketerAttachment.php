<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketerAttachment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function markerter()
    {
        return $this->hasMany(Marketer::class, 'markerter_id', 'id');
    }
}
