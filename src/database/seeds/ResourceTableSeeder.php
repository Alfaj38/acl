<?php

use Illuminate\Database\Seeder;
use Shanto\Acl\Models\Resource;

class ResourceTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\RoleController@index', false),'name'=>'Role GET::Index', 'controller'=>'Role', 'action'=>'Shanto\Acl\Http\RoleController@index']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\RoleController@create', false),'name'=>'Role GET::Create', 'controller'=>'Role', 'action'=>'Shanto\Acl\Http\RoleController@create']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\RoleController@edit', false),'name'=>'Role GET::Edit', 'controller'=>'Role', 'action'=>'Shanto\Acl\Http\RoleController@edit']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\RoleController@destroy', false),'name'=>'Role GET::Destroy', 'controller'=>'Role', 'action'=>'Shanto\Acl\Http\RoleController@destroy']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\RoleController@store', false),'name'=>'Role POST::Store', 'controller'=>'Role', 'action'=>'Shanto\Acl\Http\RoleController@store']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\RoleController@update', false),'name'=>'Role POST::Update', 'controller'=>'Role', 'action'=>'Shanto\Acl\Http\RoleController@update']);

        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\ResourceController@index', false),'name'=>'Resource GET::Index', 'controller'=>'Resource', 'action'=>'Shanto\Acl\Http\ResourceController@index']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\ResourceController@create', false),'name'=>'Resource GET::Create', 'controller'=>'Resource', 'action'=>'Shanto\Acl\Http\ResourceController@create']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\ResourceController@edit', false),'name'=>'Resource GET::Edit', 'controller'=>'Resource', 'action'=>'Shanto\Acl\Http\ResourceController@edit']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\ResourceController@destroy', false),'name'=>'Resource GET::Destroy', 'controller'=>'Resource', 'action'=>'Shanto\Acl\Http\ResourceController@destroy']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\ResourceController@store', false),'name'=>'Resource POST::Store', 'controller'=>'Resource', 'action'=>'Shanto\Acl\Http\ResourceController@store']);
        Resource::create(['resource_id'=>sha1('Shanto\Acl\Http\ResourceController@update', false),'name'=>'Resource POST::Update', 'controller'=>'Resource', 'action'=>'Shanto\Acl\Http\ResourceController@update']);
    }

}
