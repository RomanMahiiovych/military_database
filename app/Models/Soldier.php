<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soldier extends Model
{
    use HasFactory;
    protected $table = 'soldiers';

    protected $fillable = ['first_name', 'last_name', 'date_of_entry', 'phone_number', 'email', 'salary'];
}