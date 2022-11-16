<?php

namespace Oscar\Models;

use PDO;

class Location
{

    public function __construct(public PDO $db)
    {
    }

    public function insertOrFetch(string $locationName): int
    {
        $fetchLocationQuery = "SELECT id from locations WHERE name LIKE '%$locationName%'";
        $result = $this->db->query($fetchLocationQuery)->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            return $result['id'];
        }
        $query = $this->db->prepare("INSERT INTO locations (name) values(:name)");
        $query->execute(['name' => $locationName]);
        return $this->db->lastInsertId();
    }

    public function find(int $locationId)
    {
        $fetchLocationQuery = "SELECT * from locations WHERE id = $locationId";
        $result = $this->db->query($fetchLocationQuery)->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}