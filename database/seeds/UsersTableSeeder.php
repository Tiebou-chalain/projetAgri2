<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=array(
            array(
                'name'=>'Admin',
                'call'=>'655378269',
                'adresse'=>'yaounde',
                'email'=>'admin@gmail.com',
                'password'=>Hash::make('111111'),
                'role'=>'admin',
                'status'=>'active'
            ),
            array(
                'name'=>'Acheteur',
                'call'=>'655378269',
                'adresse'=>'yaounde',
                'email'=>'acheteur@gmail.com',
                'password'=>Hash::make('111111'),
                'role'=>'acheteur',
                'status'=>'active'
            ),
            array(
                'name'=>'Vendeur',
                'call'=>'655378269',
                'adresse'=>'yaounde',
                'email'=>'vendeur@gmail.com',
                'password'=>Hash::make('111111'),
                'role'=>'vendeur',
                'status'=>'active'
            ),
        );

        DB::table('users')->insert($data);
        
    }
}
