<?php

namespace App\Http\Controllers;

use App\Models\Soldier;
use Illuminate\Http\Request;

class SoldierController extends Controller
{
    public function getSoldiers()
    {
        return Soldier::get();
    }
}
