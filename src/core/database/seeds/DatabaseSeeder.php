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
        DB::table('robot_usersph')->insert([
            'first_name' => 'Benjamin Joseph',
            'last_name' => 'Silverio',
            'email' => 'benjosilverio@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $store = Store::create([
            'width' => 12,
            'height' => 12
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 0,
            'heading' => 'E',
            'commands' => 'RMMLMRMM',
            'store_id' => $store->id
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 6,
            'y' => 6,
            'heading' => 'E',
            'commands' => 'RMMLMRMM',
            'store_id' => $store->id
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 9,
            'y' => 9,
            'heading' => 'E',
            'commands' => 'RMMLMRMM',
            'store_id' => $store->id
        ]);

        $store = Store::create([
            'width' => 12,
            'height' => 12
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 0,
            'heading' => 'E',
            'commands' => 'MRMLMM',
            'store_id' => $store->id
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 3,
            'heading' => 'S',
            'commands' => 'MLMMMM',
            'store_id' => $store->id
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 7,
            'heading' => 'N',
            'commands' => 'MRRMML',
            'store_id' => $store->id
        ]);

        $store = Store::create([
            'width' => 12,
            'height' => 12
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 0,
            'heading' => 'E',
            'commands' => 'MRMLMM',
            'store_id' => $store->id
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 3,
            'heading' => 'S',
            'commands' => 'MMMMMM',
            'store_id' => $store->id
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 7,
            'heading' => 'N',
            'commands' => 'MMMMML',
            'store_id' => $store->id
        ]);



        $store = Store::create([
            'width' => 12,
            'height' => 12
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 0,
            'heading' => 'E',
            'commands' => 'MRMLMM',
            'store_id' => $store->id
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 3,
            'heading' => 'S',
            'commands' => 'RMMMMM',
            'store_id' => $store->id
        ]);

        DB::table('robot_robotsph')->insert([
            'x' => 0,
            'y' => 7,
            'heading' => 'N',
            'commands' => 'LMMMML',
            'store_id' => $store->id
        ]);


    }
}
