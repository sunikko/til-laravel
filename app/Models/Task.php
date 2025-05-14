<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Task extends Eloquent
{
    use SoftDeletes;
    
    protected $connection = 'mongodb';
    protected $collection = 'project_tasks';
    protected $fillable = ['name', 'description', 'secure_token'];
    protected $dates = ['deleted_at'];
}
