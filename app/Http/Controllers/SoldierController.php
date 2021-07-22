<?php

namespace App\Http\Controllers;

use App\Models\Soldier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\Datatables\Datatables;

class SoldierController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Soldier::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('soldiers.index');
    }

    public function create()
    {
        return View::make('soldiers.create');
    }

    public function store(Request $request)
    {
        Soldier::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_entry' => $request->date_of_entry,
            'salary' => $request->salary,
            'phone_number' => $request->phone_number,
        ]);

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
        $soldier = Soldier::find($id);

        $soldier->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_entry' => $request->date_of_entry,
            'salary' => $request->salary,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('soldiers.index')
            ->with('success', 'Soldier\'s record updated successfully.');
    }

    public function destroy($id)
    {
        Soldier::find($id)->delete();

        return redirect()->route('soldiers.index')
            ->with('success', 'Successfully deleted the soldier!');
    }
}
