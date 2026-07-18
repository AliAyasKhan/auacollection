<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id);
    public function findBySlug($slug);
    public function searchAndFilter(array $filters, $perPage = 12);
    public function getFeatured($limit = 8);
    public function getNewArrivals($limit = 8);
    public function getSaleProducts($limit = 8);
    public function getAllCategories();
    public function getAllCollections();
    public function getAllBrands();
    public function getAllColors();
    public function getAllSizes();
}
