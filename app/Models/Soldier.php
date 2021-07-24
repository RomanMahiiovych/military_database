<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soldier extends Model
{
    use HasFactory;
    protected $table = 'soldiers';

    protected $fillable = ['first_name', 'last_name', 'date_of_entry', 'phone_number', 'email', 'salary', 'rank_id'];

    public function rank()
    {
        return $this->belongsTo('Rank');
    }

    public function soldierLevel()
    {
        return $this->hasMany('SoldierHierarchy', 'soldier_id', 'id');
//        return $this->hasMany('SoldierHierarchy', 'id', 'soldier_id');
    }

    public function headLevel()
    {
        return $this->hasMany('SoldierHierarchy', 'id', 'head_id');
    }
}
