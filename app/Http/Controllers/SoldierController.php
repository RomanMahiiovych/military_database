<?php

namespace App\Http\Controllers;

use App\Http\Requests\SoldierRequest;
use App\Models\Rank;
use App\Models\Soldier;
use App\Services\SoldierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SoldierController extends Controller
{
    protected $soldierService;

    public function __construct(SoldierService $soldierService)
    {
        $this->soldierService = $soldierService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
           return $this->soldierService->getSoldiers();
        }

        return view('soldiers.index');
    }

    public function create()
    {
        return View::make('soldiers.create')
            ->with('ranks', Rank::where('id', '!=',  1)->get())
            ->with('soldiers', Soldier::get());
    }

    public function store(SoldierRequest $request)
    {
        $result = $this->soldierService->handleStoringSoldier($request);

        if (!$result) {
            return redirect()->route('soldiers.index')
                ->with('error', 'The soldier\'s record wasn\'t created successfully!');
        }

        return redirect()->route('soldiers.index')
            ->with('success', 'Soldier\'s record created successfully.');
    }

    public function show($id)
    {
        $soldier = Soldier::find($id);

        return view('soldiers.show')
            ->with('soldier', $soldier);
    }

    public function edit($id)
    {
        $soldier = Soldier::find($id);

        return view('soldiers.edit')
            ->with('soldier', $soldier);
    }

    public function update(Request $request, $id)
    {
        $result = $this->soldierService->updateSoldiers($request, $id);

        if (!$result) {
            return redirect()->route('soldiers.index')
                ->with('error', 'Soldier\'s record wasn\'t updated successfully.');
        }

        return redirect()->route('soldiers.index')
            ->with('success', 'Soldier\'s record updated successfully.');
    }

    public function destroy($id)
    {
        $this->soldierService->handleDeletingSoldier($id);

        return response()->json([
            'message' => 'Data deleted successfully!'
        ]);
    }

    public function heads($rankId)
    {
        return response()->json(Soldier::where('rank_id', --$rankId)->get());
    }
}
