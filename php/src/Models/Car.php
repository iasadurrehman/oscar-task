<?php

namespace Oscar\Models;

use PDO;

class Car
{
    const FIELDS = [
        'brand' => 'Car Brand',
        'model' => 'Car Model',
        'year' => 'Car year',
        'doors' => 'Number of doors',
        'seats' => 'Number of seats',
        'fuel_type' => 'Fuel type',
        'transmission' => 'Transmission'
    ];

    public function __construct(public PDO $db)
    {
    }

    public function getAll(int $limit = 10, $page = 1)
    {
        try {
            $startPage = ($page - 1) * $limit;
            $fetchCarQuery = "SELECT * from cars ORDER BY id ASC LIMIT $startPage, $limit";
            $paginationQuery = $this->db->prepare($fetchCarQuery);
            $paginationQuery->execute();
            $result = $paginationQuery->fetchAll(PDO::FETCH_ASSOC);
            $carMeta = new CarMeta($this->db);
            $locationModel = new Location($this->db);
            foreach ($result as &$car) {
                $meta = $carMeta->getCarMetaByCarId($car['id']);
                $location = $locationModel->find($car['location_id']);
                unset($car['location_id']);
                $car['location'] = $location;
                $car['meta'] = $meta;
            }
            return ['data' => $result, 'status' => true, 'message' => 'Success', 'code' => '200'];
        } catch (\Exception $e) {
            return ['data' => $result, 'status' => false, 'message' => $e->getMessage(), 'code' => '503'];
        }
    }

    public function find(int $carId)
    {
        try {
            $fetchCarQuery = "SELECT * from cars WHERE cars.id = $carId ";
            $result = $this->db->query($fetchCarQuery)->fetch(PDO::FETCH_ASSOC);
            $carMeta = new CarMeta($this->db);
            $locationModel = new Location($this->db);
            $location = $locationModel->find($result['location_id']);
            unset($result['location_id']);
            $result['meta'] = $carMeta->getCarMetaByCarId($carId);
            $result['location'] = $location;
            if (!empty($result)) {
                return ['data' => $result, 'status' => true, 'message' => 'Success', 'code' => '200'];
            }
        } catch (\Exception $e) {
            return ['data' => $result, 'status' => false, 'message' => $e->getMessage(), 'code' => '503'];
        }
        return ['data' => $result, 'status' => false, 'message' => 'Resource not found!', 'code' => '404'];
    }

    public function insert(array $data): int
    {
        try{
            $fields = array_keys($data);
            $values = array_values($data);
            $fieldList = implode(',', $fields);
            $qs = str_repeat("?,", count($fields) - 1);
            $query = $this->db->prepare("INSERT INTO cars ($fieldList) values(${qs}?)");
            $query->execute($values);
        }
        catch (\Exception $e){
            exit(json_encode(['status' => false, 'data' => [], 'message' => $e->getMessage()]));
        }
        return $this->db->lastInsertId();
    }
}