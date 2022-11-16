<?php

namespace Oscar\Service;

use Oscar\Classes\FileReaderInterface;
use Oscar\Config\Database;
use Oscar\Models\Car;
use Oscar\Models\CarMeta;
use Oscar\Models\Location;

class DataImportService
{
    public function __construct(private FileReaderInterface $fileReader,
                                private \PDO $db)
    {
    }

    public function import(array $transformedArray): void
    {
        if ($transformedArray) {
            $car = [];
            foreach ($transformedArray as $carData) {
                $carMeta = [];
                $location = '';
                foreach ($carData as $att => &$carDatum) {
                    if($att == 'Location'){
                        $location = $carDatum;
                        continue;
                    }
                    if (in_array($att, ['Number of doors', 'Number of seats']) && $carDatum !== null) {
                        $carDatum = abs(intval($carDatum));
                    }
                    if (array_search($att, Car::FIELDS)) {
                        $car[array_search($att, Car::FIELDS)] = $carDatum;
                    } else {
                        if ($carDatum !== null) {
                            $carMeta[] = ['meta_key' => $att, 'meta_value' => $carDatum];
                        }
                    }
                }
                try{
                    $this->db->beginTransaction();
                    $locationModel =  new Location($this->db);
                    $locationId = $locationModel->insertOrFetch($location);
                    $car['location_id'] = $locationId;
                    $carModel = new Car($this->db);
                    $carId = $carModel->insert($car);
                    $carMetaModel = new CarMeta($this->db);
                    $carMetaModel->insert($carMeta, $carId);
                    $this->db->commit();
                }
                catch (\Exception $e){
                    $this->db->rollBack();
                    $e->getMessage();
                }
            }
        }
        die;
    }

    public function read($filePath): array
    {
        return $this->fileReader->readFile($filePath);
    }
}