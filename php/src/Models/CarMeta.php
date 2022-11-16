<?php

namespace Oscar\Models;

use PDO;

class CarMeta
{

    public function __construct(public PDO $db)
    {
    }

    public function insert(array $data, int $carId): int
    {
        foreach($data as $row) {
            $fields = array_keys($row);
            $fields[] = 'car_id';
            $values = array_values($row);
            $values[] = $carId;
            $fieldList = implode(',', $fields);
            $qs = str_repeat("?,",count($fields)-1);
            $query = $this->db->prepare("INSERT INTO car_meta ($fieldList) values(${qs}?)");
            $query->execute($values);
        }
        return true;
    }
}