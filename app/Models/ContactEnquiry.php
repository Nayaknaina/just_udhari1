<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [ 'name',  'email', 'phone_number', 'subject', 'message', 'agree','is_read'];

    
    protected $casts = [
        'is_read' => 'boolean',
    ];
}

