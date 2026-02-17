<?php

namespace App\Controllers\PublicHealthMidwife;

use Library\Framework\Http\Request;

class MaternalProfileController
{
    public function index(Request $request)
    {


        return view("phm/maternalprofiles");
    }

    public function createMaternal(Request $request)
    {
        $phmId = auth()->user()->id;
        $parentId = $request->input("parent_id");
        $height = $request->input("height");
        $bloodType = $request->input("blood_type");
        $lmp = $request->input("lmp");




        // // Create Maternal Profile and Pregnancy Record
        // try {
        //     $maternalService = new \App\Services\MaternalService();
        //     $maternalService->createMaternalProfile(
        //         $parentId,
        //         $phmId,
        //         (float)$height,
        //         $bloodType,
        //         $lmp,
        //         // (int)$gravida, --- IGNORE ---
        //         // (int)$para --- IGNORE ---
        //     );

        //     return response()->json(['message' => 'Maternal profile created successfully.']);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Failed to create maternal profile: ' . $e->getMessage()], 500);
        // }
    }
}
