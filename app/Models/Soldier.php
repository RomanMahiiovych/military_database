<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soldier extends Model
{
    use HasFactory;
    protected $table = 'soldiers';

    protected $fillable = ['first_name', 'last_name', 'date_of_entry', 'phone_number', 'email', 'salary', 'rank_id', 'image', 'small_image'];

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function soldierLevel()
    {
        return $this->hasMany(SoldierHierarchy::class, 'soldier_id', 'id');
    }

    public function headLevel()
    {
        return $this->hasMany(SoldierHierarchy::class, 'head_id', 'id');
    }
}
