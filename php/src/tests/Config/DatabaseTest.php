<?php

namespace Oscar\Test\Config;

use Oscar\Config\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function setUp(): void
    {
        $this->db = new Database();
        parent::setUp();
    }

    public function testDatabaseConnect()
    {
        $this->assertNotNull($this->db->connect());
    }
}
