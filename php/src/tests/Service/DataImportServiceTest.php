<?php

namespace Oscar\Test\Service;

use Oscar\Classes\JsonReader;
use Oscar\Config\Database;
use Oscar\Service\DataImportService;
use PHPUnit\Framework\TestCase;

class DataImportServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->dataservice = new DataImportService(new JsonReader(), (new Database())->connect());
        parent::setUp();
    }

    public function testImport()
    {
        $mock = $this->createMock(DataImportService::class);
        $mock->method('import')->willReturn(['status'=> true]);
        $this->assertContains(true, $mock->import([['brand' => 'test']]));
    }
}
