<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'robot_usersph';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id','first_name', 'last_name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','created_at','updated_at'
    ];

    /**
     * The static field validators.
     *
     * @var array
     */

    public static $rules = [
        'first_name'    => 'required',                        // just a normal required validation
        'last_name'     => 'required',
        'email'         => 'required|email|unique:aktiv_usersph',     // required and must be unique in the ducks table
        'password'      => 'required|min:6',
    ];

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }

    /**
     * Set the user's last name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtolower($value);
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function getFirstNameAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Set the user's last name.
     *
     * @param  string  $value
     * @return void
     */
    public function getLastNameAttribute($value)
    {
        return ucwords($value);
    }
}
