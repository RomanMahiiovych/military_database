<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldierHierarchy extends Model
{
    use HasFactory;

    protected $table = 'soldier_hierarchies';

    protected $fillable = ['soldier_id', 'head_id', 'level'];

    public $timestamps = false;

    public function soldier()
    {
        return $this->belongsTo('Soldier', 'soldier_id', 'id');
    }

    public function head()
    {
        return $this->belongsTo('Soldier', 'head_id', 'id');
    }
}
