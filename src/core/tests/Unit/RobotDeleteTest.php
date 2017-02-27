<?php

namespace Tests\Uni;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Store;

class RobotDeleteTest extends TestCase
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
     * A test for valid request delete.
     *
     * @return void
     */
    public function testDeleteValidRequest()
    {
        $response = $this->delete('/shop/1/robot/1',[],[
            'Authorization' => "bearer " . $this->token
        ]);


        $response
            ->assertStatus(200);
    }

    /**
     * A test for invalid request delete.
     *
     * @return void
     */
    public function testDeleteInvalidRequest()
    {
        $response = $this->delete('/shop/1/robot/200',[],[
            'Authorization' => "bearer " . $this->token
        ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => ["Requested robot does not exist."]
            ]);
    }

}
