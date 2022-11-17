<?php

namespace Oscar\Service;

use Oscar\Classes\FileReaderInterface;
use Oscar\Models\Car;
use Oscar\Models\CarMeta;
use Oscar\Models\Location;

class DataImportService
{
    public function __construct(
        private FileReaderInterface $fileReader,
        private \PDO $db
    ) {
    }

    /**
     * Main import function for setting data in unified form before inserting in DB
     * Takes array of multiple cars which is transformed from CSV or JSON.
     * Turns the no. of doors and seats attribute to positive integer.
     * Map the title of columns in csv or key name in JSON to db compatible
     * column name or vice-versa (for only those columns which are compulsory there)
     * all other columns will be termed as carMeta and pushed in separate array
     * if their values are not null, otherwise ignored.
     *
     * Each car will then be added as car, location is added as location id,
     * and all other meta will be added car meta in db
     * @param array $transformedArray
     * @return array
     */
    public function import(array $transformedArray): array
    {
        if ($transformedArray) {
            $car = [];
            foreach ($transformedArray as $carData) {
                $carMeta = [];
                $location = '';
                foreach ($carData as $att => &$carDatum) {
                    if ($att == 'Location' || $att == 'location') {
                        $location = $carDatum;
                        continue;
                    }
                    if (in_array($att, ['Number of doors', 'Number of seats']) && $carDatum !== null) {
                        $carDatum = abs(intval($carDatum));
                    }
                    if (array_search($att, Car::FIELDS) || array_key_exists($att, Car::FIELDS)) {
                        $car[array_search($att, Car::FIELDS) ?: $att] = $carDatum;
                    } else {
                        if ($carDatum !== null) {
                            $carMeta[] = ['meta_key' => $att, 'meta_value' => $carDatum];
                        }
                    }
                }
                try {
                    $this->db->beginTransaction();
                    $locationModel = new Location($this->db);
                    $locationId = $locationModel->insertOrFetch($location);
                    $car['location_id'] = $locationId;
                    $carModel = new Car($this->db);
                    $carId = $carModel->insert($car);
                    $carMetaModel = new CarMeta($this->db);
                    $carMetaModel->insert($carMeta, $carId);
                    $this->db->commit();
                } catch (\Exception $e) {
                    $this->db->rollBack();
                    return ['success' => false, 'message' => $e->getMessage(), 'code' => 503];
                }
            }
            return ['success' => true, 'message' => 'success', 'code' => 201];
        }

        return ['success' => false, 'message' => 'Bad Request', 'code' => 400];
    }

    /**
     * Read function to read files from FileReaderInterface's implemented class
     * @param $filePath
     * @return array
     */
    public function read($filePath): array
    {
        return $this->fileReader->readFile($filePath);
    }
}