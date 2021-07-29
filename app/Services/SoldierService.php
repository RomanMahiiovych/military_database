<?php

namespace App\Services;

use App\Models\Soldier;
use App\Models\SoldierHierarchy;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class SoldierService
{
    public function handleDeletingSoldier($soldierId) {
        $deletedSoldier = Soldier::find($soldierId);

        //find subordinates soldiers ID
        $subordinateSoldiers = $this->getSubordinatedSoldiersId($deletedSoldier);

        if ($subordinateSoldiers) {
            $this->changeSoldiersHierarchy($deletedSoldier, $subordinateSoldiers);
        }

        //delete dependency this solder from his head
        $deletedSoldier->soldierLevel()->delete();

        //delete old head
        $deletedSoldier->delete();
    }

    public function getSubordinatedSoldiersId($deletedSoldier): array
    {
        $subordinateSoldiersHierarchies = $deletedSoldier->headLevel()->get();
        $subordinateSoldiers = [];
        if ($subordinateSoldiersHierarchies) {
            foreach ($subordinateSoldiersHierarchies as $subordinateSoldiersHierarchy) {
                $subordinateSoldiers[] = $subordinateSoldiersHierarchy->soldier_id;
            }
        }

        return $subordinateSoldiers;
    }

    public function changeSoldiersHierarchy($deletedSoldier, $subordinateSoldiers)
    {
        $deletedSoldierLevel = $deletedSoldier->soldierLevel()->first()->level;
        $subordinateSoldiersLevel = $deletedSoldierLevel + 1;

        //get head of deleted soldier
        $deletedSoldierHeadId = $deletedSoldier->soldierLevel()->first()->head_id;

        $subordinateSoldiersList = implode(',' , $subordinateSoldiers );

        //new head - a soldier who joined the army the fastest
        $newHead = Soldier::whereRaw("date_of_entry = (select min(`date_of_entry`) from soldiers where `id` IN ($subordinateSoldiersList) )")->first();

        // delete old head's dependencies
        $deletedSoldier->headLevel()->delete();

        //attach new head to higher hierarchy
        $newHead->soldierLevel()->create([
            'head_id' => $deletedSoldierHeadId,
            'level' => $deletedSoldierLevel,
        ]);

        // attach rest of soldiers to NEW HEAD
        unset($subordinateSoldiers[array_search($newHead->id, $subordinateSoldiers)]);
        foreach ($subordinateSoldiers as $subordinateSoldier) {
            $newHead->headLevel()->create([
                'soldier_id' => $subordinateSoldier,
                'level' => $subordinateSoldiersLevel,
            ]);
        }
    }

    public function handleStoringSoldier($request)
    {
        if ($request->file('photo')) {
            $imagesPath = $this->getSavedImagesPath($request->file('photo'));
        }
        $result = [];

        $soldier = Soldier::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_entry' => $request->date_of_entry,
            'salary' => $request->salary,
            'phone_number' => $request->phone_number,
            'rank_id' => $request->rank,
            'image' => $imagesPath['imagePath'],
            'small_image' => $imagesPath['smallImagePath'],
        ]);

        if (!empty($soldier)) {
            $result = SoldierHierarchy::create([
                'soldier_id' => $soldier->id,
                'level' => $soldier->rank_id,
                'head_id' => $request->head,
            ]);
        }

        if (!$result) {
            return false;
        }

        return true;
    }

    public function getSavedImagesPath($file)
    {
        $originFilename = time(). '_'. $file->getClientOriginalName();
        $smallFilename = time(). '_'. 'small_'. $file->getClientOriginalName();

        $image = Image::make($file)->resize(200,200);;
        $smallImage = Image::make($file)->resize(70,70);
        $image->save('storage/' . $originFilename);
        $smallImage->save('storage/' . $smallFilename);

        $imagePath =  'storage/' . $image->basename;
        $smallImagePath =  'storage/' . $smallImage->basename;

        return [
            'imagePath' => $imagePath,
            'smallImagePath' => $smallImagePath,
        ];
    }

    public function getSoldiers()
    {
        $soldiers = Soldier::query();

        return Datatables::of($soldiers)
            ->addColumn('action', function ($soldier) {
                $button = '<a href="soldiers/edit/' . $soldier->id.'" class="edit btn btn-primary btn-sm" id="editButton">Edit</a>';
                $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$soldier->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                return $button;
            })
            ->addColumn('rank_id', function ($soldier) {
                return $soldier->rank->rank;
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

    public function updateSoldiers($request, $id) {
        $imagesPath = [];
        if ($request->file('photo')) {
            $imagesPath = $this->getSavedImagesPath($request->file('photo'));
        }

        $soldier = Soldier::find($id);

        if (($soldier->rank_id != $request->rank)
            || $soldier->soldierLevel()->first()->head_id != $request->head) {

            //re-subordination
            if($soldier->rank_id != $request->rank) {
                $this->changeUpdatingSoldiersHierarchy($soldier);
            }

            //increase level of UPDATED SOLDIER
            SoldierHierarchy::create([
                'soldier_id' => $soldier->id,
                'level' => $request->rank,
                'head_id' => $request->head,
            ]);
        }

        $result = $soldier->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_entry' => $request->date_of_entry,
            'salary' => $request->salary,
            'phone_number' => $request->phone_number,
            'rank_id' => $request->rank,
        ]);

        if ($imagesPath) {
            $soldier->update([
                'image' =>  $imagesPath['imagePath'],
                'small_image' =>  $imagesPath['smallImagePath'],
            ]);
        }

        if (!$result) {
            $result = false;
        }

        return $result;
    }

    public function changeUpdatingSoldiersHierarchy($updatedSoldier)
    {
        //find subordinates soldiers ID
        $subordinateSoldiersId = $this->getSubordinatedSoldiersId($updatedSoldier);

        if ($subordinateSoldiersId) {
            //Updated soldier's level
            $updatedSoldierLevel = $updatedSoldier->soldierLevel()->first()->level;
            $subordinateSoldiersLevel = $updatedSoldierLevel + 1;

            //new head - a soldier who joined the army the fastest
            $newHead = Soldier::whereRaw("date_of_entry = (select min(`date_of_entry`) from soldiers where `rank_id` = $updatedSoldierLevel and `id` != $updatedSoldier->id)")->first();

            //delete old head's dependencies
            $updatedSoldier->headLevel()->delete();

            //delete previous head of UPDATED SOLDIER
            $updatedSoldier->soldierLevel()->delete();

            // attach rest of soldiers to NEW HEAD
            foreach ($subordinateSoldiersId as $subordinateSoldierId) {
                $newHead->headLevel()->create([
                    'soldier_id' => $subordinateSoldierId,
                    'level' => $subordinateSoldiersLevel,
                ]);
            }
        }
    }
}

