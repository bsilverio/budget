<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'robot_storesph';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['width','height'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];

    /**
     * The static field validators.
     *
     * @var array
     */

    public static $rules = [
        'width'     => 'required|numeric|min:2',
        'height'     => 'required|numeric|min:2',
    ];

    public function robots() {
        return $this->hasMany('App\Models\Robot', "store_id");
    }

    public function execute() {
        if(count($this->robots)) {
            $commandCount = $this->getMaxCommandCount();

            for($commandCounter = 1;$commandCounter <= $commandCount;$commandCounter++) {
                DB::beginTransaction();
                foreach($this->robots as $key => $robot) {
                    $robot->translateCommands();
                    $robot->executeCommand($commandCounter);
                }
                $outBound = $this->hasOutOfBound();
                $overLap = $this->hasOverlap();
                if($outBound || $overLap) {
                    DB::rollback();
                    return ['success' => 0, 'errors' => [$outBound ? trans('request.robot.robot_out_bound') : trans('request.robot.robot_collision') ]];
                }
                DB::commit();
            }

            return ['success' => 1];
        } else {
            return ['success' => 0, 'errors' => [trans('request.store.no_robots')]];
        }
    }

    protected function hasOverlap() {
        $result = DB::table("robot_robotsph AS r1")
                  ->join('robot_robotsph AS r2', function($result) {
                      $result->on("r1.x","=","r2.x");
                      $result->on("r1.y","=","r2.y");
                      $result->on("r1.store_id","=","r2.store_id");
                      $result->on("r1.id","<>","r2.id");

                  })
                  ->where('r1.store_id', $this->id)
                  ->count();

        return $result;
    }

    protected function hasOutOfBound() {
        $store=$this;
        $result = DB::table("robot_robotsph")
            ->where('store_id',$this->id)
            ->where(function($result) use ($store) {
                $result->where('x',"<",0)
                    ->orWhere('y',"<",0)
                    ->orWhere('x','>',$store->width)
                    ->orWhere('y','>',$store->height);
            })
            ->count();

        return $result;
    }

    protected function getMaxCommandCount() {
        $max = 0;
        foreach($this->robots as $robot) {
            $max = strlen($robot->commands) > $max ? strlen($robot->commands) : $max;
        }

        return $max;
    }
}
