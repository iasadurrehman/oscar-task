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

    /**
     * Get all Cars from Model, replaces location id with location object
     * with paginated response
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getAll(int $limit = 10, int $page = 1): array
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

    /**
     * Get a single resource of car by car id and replaces
     * the locaton_id with location model response
     * @param int $carId
     * @return array
     */
    public function find(int $carId): array
    {
        try {
            $fetchCarQuery = "SELECT * from cars WHERE cars.id = $carId ";
            $result = $this->db->query($fetchCarQuery)->fetch(PDO::FETCH_ASSOC);
            $carMeta = new CarMeta($this->db);
            $locationModel = new Location($this->db);
            if (!empty($result)) {
                $location = $locationModel->find($result['location_id']);
                $result['location'] = $location;
                $result['meta'] = $carMeta->getCarMetaByCarId($carId);
                unset($result['location_id']);
            }
            if (!empty($result)) {
                return ['data' => $result, 'status' => true, 'message' => 'Success', 'code' => '200'];
            }
        } catch (\Exception $e) {
            return ['data' => $result, 'status' => false, 'message' => $e->getMessage(), 'code' => '503'];
        }
        return ['data' => $result, 'status' => false, 'message' => 'Resource not found!', 'code' => '404'];
    }

    /**
     * Insert a car resource when car array is passed with keys and values
     * @param array $data
     * @return int
     */
    public function insert(array $data): int
    {
        try {
            $fields = array_keys($data);
            $values = array_values($data);
            $fieldList = implode(',', $fields);
            $qs = str_repeat("?,", count($fields) - 1);
            $query = $this->db->prepare("INSERT INTO cars ($fieldList) values(${qs}?)");
            $query->execute($values);
        } catch (\Exception $e) {
            exit(json_encode(['status' => false, 'data' => [], 'message' => $e->getMessage()]));
        }
        return $this->db->lastInsertId();
    }
}