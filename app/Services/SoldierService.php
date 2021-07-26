<?php

namespace App\Services;

use App\Models\Soldier;

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
}
