<?php
namespace global;
require_once 'bootstrap/app.php';
namespace Oscar\Test\Classes;

use Oscar\Classes\JsonReader;
use PHPUnit\Framework\TestCase;

class JsonReaderTest extends TestCase
{

    public function setUp(): void
    {

        $this->jsonReader = new JsonReader();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testReadFileNull()
    {
        $array = $this->jsonReader->readFile(null);
        $this->assertEmpty($array);
    }

}
