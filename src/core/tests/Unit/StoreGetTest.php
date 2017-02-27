<?php

namespace Tests\Uni;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Store;

class StoreGetTest extends TestCase
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
     * A test for valid request.
     *
     * @return void
     */
    public function testGetValidStore()
    {
        $response = $this->get('/shop/1', [
            'Authorization' => "bearer " . $this->token
        ]);


        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'width',
                'height',
                'robots'
            ]);
    }

    /**
     * A test for non-existing request.
     *
     * @return void
     */
    public function testGetNonExistingStore()
    {
        $response = $this->get('/shop/128', [
            'Authorization' => "bearer " . $this->token
        ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
              "success" => 0,
              "errors" => ["Requested shop does not exist."]
            ]);
    }

}
