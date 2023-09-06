<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];
    protected $table = 'customers';


    

    // public function readUserGroup($req)
    // {
    //     return User::where('email', $req->userEmail)
    //         ->first();
    // }

/*Add Records*/
public function insertData($req)
{
    User::create($req);
}
   
}
