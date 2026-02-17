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
        $search = $request->input("search");
        $filters = $request->input("filters");
        $phmId = auth()->user()->id;
       [ $maternals,$links] = $this->maternalService->getMaternalByPhmId($phmId, $search, $filters);
        $unMaternalProfiles = $this->maternalService->getParentsWithoutMaternal($phmId);
        return view("phm/maternalprofiles", ['maternals' => $maternals, 'unMaternalProfiles' => $unMaternalProfiles,'links'=> $links]);
    }

    public function createMaternal(Request $request)
    {
        $phmId = auth()->user()->id;
        $parentId = $request->input("parent_id");
        $height = $request->input("height");
        $bloodType = $request->input("blood_type");
        $lmp = $request->input("lmp");

        $errors = $this->maternalService->validateMaternalProfile($height, $lmp, $bloodType);

        if (count($errors) > 0) {
            return redirect(route('phm.maternal.profiles'))
                ->withErrors($errors)
                ->withInput([
                    'parent_id' => $parentId,
                    'height' => $height,
                    'blood_type' => $bloodType,
                    'lmp' => $lmp,
                ])
                ->with("create", true);
        }


        $error = $this->maternalService->createMaternalProfile($parentId, $phmId, $height, $bloodType, $lmp);

        if ($error) {
            return redirect(route('phm.maternal.profiles'))
                ->withMessage($error, "Error", "error");
        }

        return redirect(route('phm.maternal.profiles'))->withMessage(
            "Maternal profile was successfully created",
            "Success",
            "success",
        );
    }

    public function endAntenatal(Request $request, $id)
    {
        $endAt = $request->input("end_at");
        $deliveryOutcome = $request->input("delivery_outcome");

        $errors = $this->maternalService->validateAntenatalEndData($endAt, $deliveryOutcome);

        if (count($errors) > 0) {
            return redirect(route('phm.maternal.profiles'))
                ->withErrors($errors)
                ->withInput([
                    'end_at' => $endAt,
                    'delivery_outcome' => $deliveryOutcome,
                ])
                ->with("end", true);
        }

        $error = $this->maternalService->endAntenatalCare($id, $deliveryOutcome, $endAt);

        if ($error) {
            return redirect(route('phm.maternal.profiles'))
                ->withMessage($error, "Error", "error");
        }

        return redirect(route('phm.maternal.profiles'))->withMessage(
            "Antenatal period ended successfully",
            "Success",
            "success",
        );
    }
}
