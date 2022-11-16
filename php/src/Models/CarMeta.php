<?php

namespace Oscar\Models;

use Exception;
use PDO;

class CarMeta
{

    public function __construct(public PDO $db)
    {
    }

    public function insert(array $data, int $carId): int
    {
        foreach ($data as $row) {
            $fields = array_keys($row);
            $fields[] = 'car_id';
            $values = array_values($row);
            $values[] = $carId;
            $fieldList = implode(',', $fields);
            $qs = str_repeat("?,", count($fields) - 1);
            $query = $this->db->prepare("INSERT INTO car_meta ($fieldList) values(${qs}?)");
            $query->execute($values);
        }
        return true;
    }

    public function findByCarId(int $carId)
    {
        try {
            $result = $this->getCarMetaByCarId($carId);
            return ['data' => $result, 'status' => true, 'message' => 'Success', 'code' => '200'];
        } catch (Exception $exception) {
            return ['data' => [], 'status' => false, 'message' => 'Some error occured', 'code' => '500'];
        }
        print_r($result);
        die;
    }

    public function getCarMetaByCarId(int $carId)
    {
        $fetchMetaCarQuery = "SELECT * from car_meta WHERE car_meta.car_id = $carId ";
        $resultCarMeta = $this->db->query($fetchMetaCarQuery)->fetchAll(PDO::FETCH_ASSOC);
        return $resultCarMeta;
    }
}