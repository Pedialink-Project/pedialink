<?php

namespace App\Controllers\Parent;
use App\Services\Parent\DashboardService;
class DashboardController
{

 private $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }
    public function index()
    {

        $events = $this->dashboardService->getEventsData();
        $appointmentCount = $this->dashboardService->getAppointmentCount();
        $childAppointments = $this->dashboardService->getLatestChildAppointmentsByParentId();
        $maternalAppointments = $this->dashboardService->getLatestMaternalAppointmentsByParentId();
        $bmiData = $this->dashboardService->getChildrenBmiData();
        $childList = $this->dashboardService->getLinkedChildrenListByParentId();
        $childCount = $this->dashboardService->getChildrenCount();
        $vaccinations = $this->dashboardService->getChildVaccinationByParentId();
        $vaccinationCount = $this->dashboardService->getChildVaccinationCountByParentId();

        var_dump($maternalAppointments);
        return view("parent/dashboard", ['events' => $events, 'appointmentCount' => $appointmentCount,'childAppointments' => $childAppointments, 'maternalAppointments' => $maternalAppointments, 'childCount' => $childCount, 'bmiData' => $bmiData, 'childrenList' => $childList,'vaccinations'=>$vaccinations, 'vaccinationCount'=>$vaccinationCount]);
    }
}