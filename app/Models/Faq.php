<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use Notifiable,SoftDeletes;
    protected $table = 'faq';
    protected $fillable = ['category','status'];
}
