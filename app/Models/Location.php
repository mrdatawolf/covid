<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 *
 * @property string $title
 * @property string $county
 * @property string $state
 * @package App\Models
 */
class Location extends Model
{
    public    $timestamps = true;
    protected $table      = 'locations';
    protected $fillable   = ['title, county, state'];
}
