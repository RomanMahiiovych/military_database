<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use App\Models\Soldier;
use App\Models\SoldierHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use PHPUnit\Exception;
use Yajra\Datatables\Datatables;

class SoldierController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $soldiers = Soldier::query();

            return Datatables::of($soldiers)
                ->addColumn('action', function ($soldier) {
                    $button = '<a href="soldiers/edit/' . $soldier->id.'" class="edit btn btn-primary btn-sm" id="editButton">Edit</a>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$soldier->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                    return $button;
                })
                ->addColumn('image', function ($soldier) {
                $url = asset($soldier->small_image);
                $anonymousImageUrl = asset('storage/' . 'anonymous_user.png');
                $image = (!empty($soldier->small_image))
                    ? '<img src=" '.$url.' " border="0" class="img-rounded" align="center" />'
                    : '<img src=" '.$anonymousImageUrl.' " border="0" width="80" class="img-rounded" align="center" />';
                return $image;
                })
                ->rawColumns(['image', 'action'])
                ->toJson();
        }

        return view('soldiers.index');
    }

    public function create()
    {
        return View::make('soldiers.create')
            ->with('ranks', Rank::where('id', '!=',  1)->get())
            ->with('soldiers', Soldier::get());
    }

    public function store(Request $request)
    {
        $file = $request->file('photo');
        $originFilename = time(). '_'. $file->getClientOriginalName();
        $smallFilename = time(). '_'. 'small_'. $file->getClientOriginalName();

        $image = Image::make($file);
        $smallImage = Image::make($file)->resize(70,70);
        $image->save('storage/' . $originFilename);
        $smallImage->save('storage/' . $smallFilename);

        $soldier = Soldier::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_entry' => $request->date_of_entry,
            'salary' => $request->salary,
            'phone_number' => $request->phone_number,
            'rank_id' => $request->rank,
            'image' =>  'storage/' . $image->basename,
            'small_image' =>  'storage/' . $smallImage->basename,
        ]);

        if (!empty($soldier)) {
            SoldierHierarchy::create([
                'soldier_id' => $soldier->id,
                'level' => $soldier->rank_id,
                'head_id' => $request->head,
            ]);
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
        $soldier = Soldier::find($id);

        try {
            $level = $soldier->soldierLevel()->first();
        } catch (Exception $e) {
            return response()->json([
                'errors' => 'The soldier was not deleted successfully!'
            ]);
        }

        if ($level) {
            $level->delete();
        }
        $soldier->delete();

        return response()->json([
            'message' => 'Data deleted successfully!'
        ]);
    }

    public function heads($rankId)
    {
        return response()->json(Soldier::where('rank_id', --$rankId)->get());
    }
}
