<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Store;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bdt_usersph')->insert([
            'first_name' => 'Benjamin Joseph',
            'last_name' => 'Silverio',
            'email' => 'benjosilverio@gmail.com',
            'password' => bcrypt('liam.123'),
        ]);

    }
}
