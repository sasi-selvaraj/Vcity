<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBooking extends Model
{
    use HasFactory;
    protected $table = 'customer_booking';
    protected $fillable = ['project_id','plot_id','customer_name','director_id'];
}
