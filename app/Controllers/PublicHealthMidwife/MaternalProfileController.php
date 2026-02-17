<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Services\MaternalService;
use Library\Framework\Http\Request;

class MaternalProfileController
{

    private $maternalService;
    public function __construct()
    {
        $this->maternalService = new MaternalService();
    }
    public function index(Request $request)
    {
        $phmId = auth()->user()->id;
        $unMaternalProfiles = $this->maternalService->getParentsWithoutMaternal($phmId);
        return view("phm/maternalprofiles", ['unMaternalProfiles' => $unMaternalProfiles]);
    }

    // public function createMaternal(Request $request)

    //     // $parentId = $request->input("parent_id");
    //     // $height = $request->input("height");
    //     // $bloodType = $request->input("blood_type");
    //     // $lmp = $request->input("lmp");




    //     // // Create Maternal Profile and Pregnancy Record
    //     // try {
    //     //     $maternalService = new \App\Services\MaternalService();
    //     //     $maternalService->createMaternalProfile(
    //     //         $parentId,
    //     //         $phmId,
    //     //         (float)$height,
    //     //         $bloodType,
    //     //         $lmp,
    //     //         // (int)$gravida, --- IGNORE ---
    //     //         // (int)$para --- IGNORE ---
    //     //     );

    //     //     return response()->json(['message' => 'Maternal profile created successfully.']);
    //     // } catch (\Exception $e) {
    //     //     return response()->json(['error' => 'Failed to create maternal profile: ' . $e->getMessage()], 500);
    //     // }
    // }
}
