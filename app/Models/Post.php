<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Post
 *
 * This class represents the Post model, which maps to the 'posts' table in the database.
 * It uses the HasFactory and Notifiable traits provided by Laravel.
 */
class Post extends Model
{
    use HasFactory, Notifiable;

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     * Setting this to false because the 'posts' table does not have the default timestamp fields.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * These are the fields that can be set using mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'abstract',
        'content',
        'posted',
    ];
}
