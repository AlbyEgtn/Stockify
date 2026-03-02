<?php

namespace App\Services\Staff;

use App\Repositories\Staff\StaffDashboardRepository;

class StaffDashboardService
{
    protected $repository;

    public function __construct(StaffDashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDashboardData()
    {
        return [
            'totalProducts' => $this->repository->getTotalProducts(),
            'lowStock'      => $this->repository->getLowStockProducts(5),
            'incomingToday' => $this->repository->getTodayIncoming(),
            'outgoingToday' => $this->repository->getTodayOutgoing(),
            'incomingTasks' => $this->repository->getPendingIncoming(),
            'outgoingTasks' => $this->repository->getPendingOutgoing(),
        ];
    }
}
