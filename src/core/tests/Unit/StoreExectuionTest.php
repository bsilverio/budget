<?php

namespace Tests\Uni;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Store;

class StoreExecutionTest extends TestCase
{
    protected $token;

    public function setUp() {
        parent::setUp();

        $response = $this->post('/login', [
            'email' => 'benjosilverio@gmail.com',
            'password' => 'password'
        ]);

        $result = json_decode($response->getContent(), true);

        $this->token = $result['response']['token'];
    }

    /**
     * A test for valid request and successful execution.
     *
     * @return void
     */
    public function testSuccessExecution()
    {
        $response = $this->post('/shop/2/execute', [],
            [
                'Authorization' => "bearer " . $this->token
            ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                "id" => 2,
                "width" => 12,
                "height" => 12,
                "robots" => [
                    [
                        "id" => 4,
                        "x" => 3,
                        "y" => 1,
                        "heading" => "E",
                        "commands" => "MRMLMM"
                    ],
                    [
                        "id" => 5,
                        "x" => 4,
                        "y" => 4,
                        "heading" => "E",
                        "commands" => "MLMMMM"
                    ],
                    [
                        "id" => 6,
                        "x" => 0,
                        "y" => 8,
                        "heading" => "E",
                        "commands" => "MRRMML"
                    ]
                ]
            ]);
    }

    /**
     * A test for valid request and execution with collision.
     *
     * @return void
     */
    public function testCollisionExecution()
    {
        $response = $this->post('/shop/3/execute', [],
            [
                'Authorization' => "bearer " . $this->token
            ]);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                "success"  => 0,
                "errors"  => ["A robot had a collision with another robot."]
            ]);
    }

    /**
     * A test for valid request and execution with robot going out of bounds.
     *
     * @return void
     */
    public function testOutOfBoundExecution()
    {
        $response = $this->post('/shop/4/execute', [],
            [
                'Authorization' => "bearer " . $this->token
            ]);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                "success"  => 0,
                "errors"  => ["A robot went out of store's bounds."]
            ]);
    }

}
