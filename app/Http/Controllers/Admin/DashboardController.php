<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepositoryInterface;

class DashboardController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        // Fetch dashboard statistics
        $stats = $this->orderRepository->getStatistics();
        return view('admin.dashboard', compact('stats'));
    }
}
