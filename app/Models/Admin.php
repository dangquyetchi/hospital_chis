<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'tbl_admin';  // Tên bảng
    protected $fillable = [
        'admin_email',
        'admin_password',
        'admin_name',
        'admin_phone',
    ];

    protected $hidden = [
        'admin_password',
    ];
}