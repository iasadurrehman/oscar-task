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

    public function insert(array $data): int
    {
        $fields = array_keys($data);
        $values = array_values($data);
        $fieldList = implode(',', $fields);
        $qs = str_repeat("?,",count($fields)-1);
        $query = $this->db->prepare("INSERT INTO cars ($fieldList) values(${qs}?)");
        $query->execute($values);
        return $this->db->lastInsertId();
    }

}