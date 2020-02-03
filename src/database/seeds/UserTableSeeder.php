<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder {
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->_create(['name' => 'Mr. Developer', 'email' => 'developer@example.com', 'password' => bcrypt('123456'), 'role_id' => '1','is_active'=>true]);
        $this->_create(['name' => 'Mr. Admin', 'email' => 'admin@example.com', 'password' => bcrypt('123456'), 'role_id' => '2','is_active'=>true]);
    }
    
    private function _create(array $data){
        return User::create($data);
    }

}
