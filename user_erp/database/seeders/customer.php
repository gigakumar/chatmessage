<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class customer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
       $myuser= new User();

        $myuser->name= 'nana';
        $myuser->email='nana@gmail.com';
        $myuser->mobile='1234567896';
        $myuser->password=Hash::make('nana1');
        $myuser->save();

    }
}
