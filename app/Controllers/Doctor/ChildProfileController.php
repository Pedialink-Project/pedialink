<?php

namespace App\Controllers\Doctor;

use App\Models\Area;
use App\Services\ChildService;
use Library\Framework\Http\Request;

class ChildProfileController
{
    private $childService;
    
    public function __construct()
    {
        $this->childService = new ChildService();
    }
}