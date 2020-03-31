<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyScrum extends Model
{

    public function users(){
        return $this->belongsTo('App\User');
    }

    protected $table="daily_scrum";
}
