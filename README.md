# Oscar Rental Task

Oscar task is developed by Asad Ur Rehman for interview round 3. Built using core PHP

## Deployment Instructions

- Clone repository
- run ```docker-compose build```
- run ```docker-compose up -d```
- run ```docker-compose exec -it php-apache-environment /bin/bash/``` to get into docker container and run ```composer install```
- Connect mysql on localhost, create database with name ```'oscar'``` and import sql dump included in repository
- Open localhost on browser and check it's running

Routes to be used:

| Route | Method | Function | Usage |
| ----- | ------ | ------- | ------ |
| ```/api/cars/?limit=10&page=1``` | ```GET``` | Get all cars from system in paginated response | query params: ```limit```,  ```page``` Default page=1, limit=10 
| ```/api/car-meta?car_id={car_id}``` | ```GET``` | Get all car meta of provided car id | -
| ```/api/car/{id}``` | ```GET``` | Get car resource by car id | -
| ```/api/car/``` | ```POST``` | Create a new Car resource | Data should be provided in ```application/json```
| ```/api/car/import-data/``` | ```POST``` | Import CSV/JSON files of cars | ```file``` key to be used in POST request
Example:
```sh
http://localhost/api/car/45
```
## Checklist

- ✅  API-First mentality (routes names as per standard)
- ✅  PSR-12  Coding standards
- ✅  Reading CSV/JSON Filetypes
- ✅  Different Structures Supported
- ✅  Created service to assemble data in unified structure
- ✅  Scalable schema to best of my assumptions after studying the data in given data sources
- ✅  Series of endpoints implemented to ONLY retrieve data from database
- ✅  Endpoint implmented for creating car
- ✅  Created endpoint for import file as well
- ✅  Covered with few test cases  and TDD approach
- ✅  Built on top of docker
- ✅  Deployment instructions included


## Tech

- Docker
- PHP8.0
- MySQL 8.0

## Database Schema Explanation

#### Tables
- cars
- car_meta
- locations

#### Explanation
Going through the data provided in challenge in given sources, there were some fields which were available in all three sources, hence I found it more feasible to keep their column in cars table, and keep all other attributes of car in car_meta as these can be more or less, or maybe different in the future as well. Since extra columns are uncertain, I kept in car_meta.

Locations table is used to store locations in data. There can be difference locations. They will be added in this table when the location of the same name is not in the list (keeping it case-sensitive for now to keep it simple); otherwise the location id will be returned of location which is already in the table, which will be used in car's table column location_id.

## Codebase
```FileReaderInterface``` is used to cater different type of file in the future. Just extending it and keeping all the code same.
```DataImportService``` to combine the data in a single unified form before pushing it into database.
```/api/``` directory for all the endpoints

If there's any issue in deployment or in understanding, please contact me at: iAsadUrRehman@hotmail.com
