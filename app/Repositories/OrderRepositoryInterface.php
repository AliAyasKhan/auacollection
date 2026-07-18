<?php

namespace App\Repositories;

interface OrderRepositoryInterface
{
    public function create(array $orderData, array $itemsData, array $shippingData, array $paymentData);
    public function find($id);
    public function findByOrderNumber($orderNumber);
    public function getUserOrders($userId, $perPage = 10);
    public function getAllOrdersPaginated(array $filters = [], $perPage = 15);
    public function updateStatus($orderId, $status);
    public function addProofOfPayment($orderId, $filePath, $paymentMethod);
    public function getStatistics();
}
