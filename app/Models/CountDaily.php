<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CountDaily
 *
 * @property integer $count
 * @property         $location
 *
 * @package App\Models
 */
class CountDaily extends Model
{
    public    $timestamps = true;
    protected $table      = 'count_daily';
    protected $fillable   = ['count', 'created_at', 'location_id'];

    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Location');
    }
}
