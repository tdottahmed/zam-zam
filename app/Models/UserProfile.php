<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contact_no',
        'company_name',
        'website',
        'job_title',
        'fax',
        'tax_id',
        'bank_name',
        'bank_account_no',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
