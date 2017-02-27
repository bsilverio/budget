<?php

namespace Tests\Uni;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Store;

class StoreCreateTest extends TestCase
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
     * A test for valid request input.
     *
     * @return void
     */
    public function testCreateValidInput()
    {
        $response = $this->post('/shop', [
            'width' => 12,
            'height' => 12,
        ],
        [
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
     * A test for no width parameter request input.
     *
     * @return void
     */
    public function testCreateNoWidthInput()
    {
        $response = $this->post('/shop', [
            'height' => 12,
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                'success' => 0,
                'errors' => ['width' => ["Store width is required."]]
            ]);
    }

    /**
     * A test for no height parameter request input.
     *
     * @return void
     */
    public function testCreateNoHeightInput()
    {
        $response = $this->post('/shop', [
            'width' => 12,
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                'success' => 0,
                'errors' => ['height' => ["Store height is required."]]
            ]);
    }

    /**
     * A test for no parameter request input.
     *
     * @return void
     */
    public function testCreateNoInput()
    {
        $response = $this->post('/shop', [],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                'success' => 0,
                'errors' => ['height' => ["Store height is required."], 'width' => ["Store width is required."]]
            ]);
    }

    /**
     * A test for low input parameter value.
     *
     * @return void
     */
    public function testCreateLowValueInput()
    {
        $response = $this->post('/shop', [
            'width' => 1,
            'height' => 1,
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                'success' => 0,
                'errors' => ['height' => ["Store minimum height is 2."], 'width' => ["Store minimum width is 2."]]
            ]);
    }

}
