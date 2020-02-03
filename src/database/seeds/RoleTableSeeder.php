<?php

use Illuminate\Database\Seeder;
use Alfaj\Acl\Models\Role;

class RoleTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {        
        Role::create(['name'=>'Developer']);
        Role::create(['name'=>'Default']);
    }

}
