<?php

namespace App\Controllers\Doctor;
use App\Services\MaternalRecordService;
use Library\Framework\Http\Request;
use App\Models\ParentM;


class MaternalHealthController
{
    protected $maternalRecordService;

    public function __construct()
    {
        $this->maternalRecordService = new MaternalRecordService();
    }

}