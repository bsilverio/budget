<?php

namespace Tests\Uni;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Store;

class RobotCreateTest extends TestCase
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
     * A test for valid request but same spot.
     *
     * @return void
     */
    public function testCreateValidInput()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 7,
            'y' => 3,
            'heading' => 'N',
            'commands' => 'RRMMLMRM'
        ],
        [
            'Authorization' => "bearer " . $this->token
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'x',
                'y',
                'heading',
                'commands'
            ]);
    }

    /**
     * A test for valid request input.
     *
     * @return void
     */
    public function testCreateValidInputSameSpot()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 0,
            'y' => 0,
            'heading' => 'N',
            'commands' => 'RRMMLMRM'
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);

        $response
            ->assertStatus(422)
            ->assertExactJson([
                'success' => 0,
                'errors' => ['store' => ["A robot has already been positioned on that coordinate."]]
            ]);
    }

    /**
     * A test for no request input.
     *
     * @return void
     */
    public function testNoInput()
    {
        $response = $this->post('/shop/1/robot', [],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "x" => ["X coordinate location is required."],
                    "y" => ["Y coordinate location is required."],
                    "heading" => ["Robot heading is required."],
                    "commands" => ["Robot commands are required.","Command has an invalid value."]
                ]
            ]);

    }

    /**
     * A test for Invalid x input with non-existing other fields.
     *
     * @return void
     */
    public function testWithInvalidMinXNoInput()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => -1
        ],
        [
            'Authorization' => "bearer " . $this->token
        ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "x" => ["X coordinate should be greater than or equal to 0."],
                    "y" => ["Y coordinate location is required."],
                    "heading" => ["Robot heading is required."],
                    "commands" => ["Robot commands are required.","Command has an invalid value."]
                ]
            ]);

    }

    /**
     * A test for Invalid x input with non-existing other fields.
     *
     * @return void
     */
    public function testWithInvalidNumericXNoInput()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 'K'
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "x" => ["X coordinate should be a number."],
                    "y" => ["Y coordinate location is required."],
                    "heading" => ["Robot heading is required."],
                    "commands" => ["Robot commands are required.","Command has an invalid value."]
                ]
            ]);

    }

    /**
     * A test for Invalid x input with non-existing other fields.
     *
     * @return void
     */
    public function testWithInvalidBoundsXNoInput()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 1200
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "x" => ["X coordinate should be within the shop's size."],
                    "y" => ["Y coordinate location is required."],
                    "heading" => ["Robot heading is required."],
                    "commands" => ["Robot commands are required.","Command has an invalid value."]
                ]
            ]);

    }

    /**
     * A test for with valid x, Invalid y input with non-existing other fields.
     *
     * @return void
     */
    public function testWithXWithInvalidMinYNoInput()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 0,
            'y' => -1
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "y" => ["Y coordinate should be greater than or equal to 0."],
                    "heading" => ["Robot heading is required."],
                    "commands" => ["Robot commands are required.","Command has an invalid value."]
                ]
            ]);

    }

    /**
     * A test for Valid X, Invalid y input with non-existing other fields.
     *
     * @return void
     */
    public function testWithXWithInvalidNumericYNoInput()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 0,
            'y' => 'K'
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "y" => ["Y coordinate should be a number."],
                    "heading" => ["Robot heading is required."],
                    "commands" => ["Robot commands are required.","Command has an invalid value."]
                ]
            ]);

    }

    /**
     * A test for Valid X, Invalid y input with non-existing other fields.
     *
     * @return void
     */
    public function testWithXWithInvalidBoundsYNoInput()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 0,
            'y' => 1200
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "y" => ["Y coordinate should be within the shop's size."],
                    "heading" => ["Robot heading is required."],
                    "commands" => ["Robot commands are required.","Command has an invalid value."]
                ]
            ]);
    }

    /**
     * A test for Valid X,Y, Invalid heading input with no commands.
     *
     * @return void
     */
    public function testWithXYWithInvalidAlphaHeadingNoCommands()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 3,
            'y' => 9,
            'heading' => 56
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "heading" => ["Heading provided can only be: N,E,S,W."],
                    "commands" => ["Robot commands are required.","Command has an invalid value."]
                ]
            ]);
    }

    /**
     * A test for invalid commands.
     *
     * @return void
     */
    public function testWithInvalidCommandAlpha()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 4,
            'y' => 1,
            'heading' => 'N',
            'commands' => 56
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "commands" => ["Robot commands can only contain the characters: L, R, M","Command has an invalid value."]
                ]
            ]);
    }

    /**
     * A test for invalid commands.
     *
     * @return void
     */
    public function testWithInvalidCommandIn()
    {
        $response = $this->post('/shop/1/robot', [
            'x' => 5,
            'y' => 8,
            'heading' => 'N',
            'commands' => "LRMMMLRRRLMMMD"
        ],
            [
                'Authorization' => "bearer " . $this->token
            ]);


        $response
            ->assertStatus(422)
            ->assertExactJson([
                "success" => 0,
                "errors" => [
                    "commands" => ["Command has an invalid value."]
                ]
            ]);
    }
}
