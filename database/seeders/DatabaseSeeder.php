<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'name'=>'admin',
        //     //'prenom'=>'admin',
        //     'email'=>'admin@gmail.com',
        //     'password'=>Hash::make('admin'),
        //     'role'=>'admin',
        //     'statut'=>'active',
        //     // 'profil_terminer'=>true,
        //     // 'is_email_verified'=>true,
        // ]);

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
