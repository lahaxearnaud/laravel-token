<?php namespace Lahaxearnaud\LaravelToken\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Token
 *
 * @property integer        $id
 * @property string         $token
 * @property boolean        $login
 * @property integer        $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $expire_at
 * @property-read \User $user
 * @method static \Illuminate\Database\Query\Builder|\Lahaxearnaud\LaravelToken\Models\Token whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Lahaxearnaud\LaravelToken\Models\Token whereToken($value) 
 * @method static \Illuminate\Database\Query\Builder|\Lahaxearnaud\LaravelToken\Models\Token whereUserId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Lahaxearnaud\LaravelToken\Models\Token whereExpireAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Lahaxearnaud\LaravelToken\Models\Token whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Lahaxearnaud\LaravelToken\Models\Token whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Lahaxearnaud\LaravelToken\Models\Token whereLogin($value) 
 */
class Token extends Model
{


    protected $guarded = array();

    /**
     * Get the post's author.
     *
     * @return \User
     */
    public function user()
    {

        return $this->belongsTo('User', 'user_id');
    }

    public function getDates()
    {

        return array_merge(parent::getDates(), ['expire_at']);
    }
}
