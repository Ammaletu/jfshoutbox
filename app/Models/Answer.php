<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The answer model contains a single answer to a shoutbox question.
 *
 * @package App\Models
 */
class Answer extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'nickname', 'answer', 'status'
	];
	
	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'status' => 'published'
	];
}
