<?php

namespace Omatech\LaravelDoneJobs;

use Illuminate\Database\Eloquent\Model;

class DoneJob extends Model
{
    public $fillable = ['connection', 'queue', 'job', 'payload', 'attempts', 'done_at'];
}
