<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Config;

use Illuminate\Support\Facades\DB;

class Robot extends Model
{
    protected $commandsArray;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'robot_robotsph';

    protected $guarded = ['x','y','heading','commands'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'store_id','created_at','updated_at'
    ];

    /**
     * The static field validators.
     *
     * @var array
     */

    public static $rules = [
        'store_id'         => 'required|numeric|exists:robot_storesph,id',
        'x'     => 'required|numeric|min:0',
        'y'     => 'required|numeric|min:0',
        'heading'        => 'required|in:N,E,S,W',
        'commands' => 'required|alpha'
    ];

    /**
     * The static update field validators.
     *
     * @var array
     */

    public static $updateRules = [
        'x'     => 'sometimes|required|numeric|min:0',
        'y'     => 'sometimes|required|numeric|min:0',
        'heading'        => 'sometimes|required|in:N,E,S,W',
        'commands' => 'sometimes|required|alpha'
    ];

    public static function customValidateInput($input, $store, $errors,$robot=null) {
        if(array_key_exists('commands', $input)) {
            $commands = str_split($input['commands']);

            foreach($commands as $command) {
                if(!array_key_exists($command, Config::get("robot.command_values"))) {
                    if(array_key_exists('commands', $errors)) {
                        $errors['commands'][] = trans('validation_messages.robot_validation.commands.invalid');
                    } else {
                        $errors['commands'] = [trans('validation_messages.robot_validation.commands.invalid')];
                    }
                    break;
                }
            }
        }

        if(array_key_exists('x', $input)) {
            if($input['x'] > $store->width) {
                if(array_key_exists('x', $errors)) {
                    $errors['x'][] = trans('validation_messages.robot_validation.x.out_of_bound');
                } else {
                    $errors['x'] = [trans('validation_messages.robot_validation.x.out_of_bound')];
                }
            }

            if(!array_key_exists('y', $input)) {
                if(!array_key_exists("x", $errors)) {
                    if ($robot) {
                        $count = DB::table('robot_robotsph')->where("x", $input['x'])->where("y", $robot->y)->count();

                        if ($count) {
                            $errors['store'] = [trans("validation_messages.robot_validation.store.spot_taken")];
                        }
                    }
                }
            }
        }

        if(array_key_exists('y', $input)) {
            if($input['y'] > $store->height) {
                if(array_key_exists('y', $errors)) {
                    $errors['y'][] = trans('validation_messages.robot_validation.y.out_of_bound');
                } else {
                    $errors['y'] = [trans('validation_messages.robot_validation.y.out_of_bound')];
                }
            }

            if(!array_key_exists('x', $input)) {
                if(!array_key_exists("y", $errors)) {
                    if($robot) {
                        $count = DB::table('robot_robotsph')->where("x",$robot->x)->where("y", $input['y'])->count();

                        if($count) {
                            $errors['store'] = [trans("validation_messages.robot_validation.store.spot_taken")];
                        }
                    }
                }
            }

        }
        if(array_key_exists('x', $input) && array_key_exists('x', $input)) {
            if(!array_key_exists('y', $errors) && !array_key_exists('x', $errors)) {
                $count = DB::table('robot_robotsph')->where("x",$input['x'])->where("y", $input['y'])->count();
                if($count) {
                    $errors['store'] = [trans("validation_messages.robot_validation.store.spot_taken")];
                }
            }
        }

        if(count($errors)) {
            return ['success' => 0,'errors' => $errors];
        } else {
            return ['success' => 1];
        }
    }

    public function store() {
        return $this->belongsTo('App\Models\Store', "store_id");
    }

    public function translateCommands() {
        $commandValues = Config::get('robot.command_values');

        $this->commandsArray = str_split($this->commands);

        foreach($this->commandsArray as $key => $command) {
            $this->commandsArray[$key] = $commandValues[$command];
        }
    }

    public function executeCommand($count) {
        if(array_key_exists($count - 1, $this->commandsArray)) {
            $command = $this->commandsArray[$count - 1];
            $movementValue = Config::get('robot.movement_values');
            $directions = Config::get('robot.directions');
            if($command === 0) {
                $this->{$movementValue[$this->heading][0]} += $movementValue[$this->heading][1];
            } else {
                $key = array_search($this->heading, $directions);
                $newKey = $key + $command < 0 ? count($directions) - 1 : $key + $command;
                $newKey = $newKey > count($directions) - 1 ? 0 : $newKey;
                $this->heading = $directions[$newKey];
            }

            $this->save();
        }
    }
}
