<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CountDaily
 *
 * @property integer $count
 * @package App\Models
 */
class CountDaily extends Model
{
    public    $timestamps = true;
    protected $table      = 'count_daily';
    protected $fillable   = ['count', 'created_at'];
}
