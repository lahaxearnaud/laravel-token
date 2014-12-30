<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use \Lahaxearnaud\LaravelToken\models\UserTokenInterface;

class User extends Model implements UserInterface, RemindableInterface, UserTokenInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    /**
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return boolean
     */
    public function loggableByToken ()
    {
        return $this->id == 1;
    }
}
