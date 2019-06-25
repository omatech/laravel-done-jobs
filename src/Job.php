<?php

namespace Omatech\LaravelDoneJobs;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public $fillable = ['queue', 'payload', 'attempts', 'available_at', 'created_at'];

    public $timestamps = false;
}
