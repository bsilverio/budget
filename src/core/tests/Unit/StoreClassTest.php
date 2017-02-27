<?php

namespace Tests\Uni;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Store;

class StoreClassTest extends TestCase
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
        $validators = Store::$rules;

        $this->assertArrayHasKey('width',$validators);
        $this->assertArrayHasKey('height',$validators);
        $this->assertClassHasStaticAttribute('rules', Store::class);
    }

}
