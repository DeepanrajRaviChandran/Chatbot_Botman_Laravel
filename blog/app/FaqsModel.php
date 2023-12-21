<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaqsModel extends Model
{
	protected $table = 'faqsdeepan';
	protected $fillable = ['title', 'description'];
}
