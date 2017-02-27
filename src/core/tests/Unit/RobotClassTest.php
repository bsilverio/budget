<?php

namespace Tests\Uni;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Robot;

class RobotClassTest extends TestCase
{

    public function setUp() {
        parent::setUp();
    }

    /**
     * A test for static validators.
     *
     * @return void
     */
    public function testStaticValidators()
    {
        $validators = Robot::$rules;

        $this->assertArrayHasKey('x',$validators);
        $this->assertArrayHasKey('y',$validators);
        $this->assertArrayHasKey('heading',$validators);
        $this->assertArrayHasKey('commands',$validators);
        $this->assertClassHasStaticAttribute('rules', Robot::class);
        $this->assertClassHasStaticAttribute('updateRules', Robot::class);
    }

}
