<?php

use Illuminate\Database\Seeder;
use Pollob666\Acl\Models\Resource;

class ResourceTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\RoleController@index', false),'name'=>'Role List', 'controller'=>'Role', 'action'=>'Pollob666\Acl\Http\RoleController@index']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\RoleController@create', false),'name'=>'Role Create', 'controller'=>'Role', 'action'=>'Pollob666\Acl\Http\RoleController@create']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\RoleController@edit', false),'name'=>'Role Edit', 'controller'=>'Role', 'action'=>'Pollob666\Acl\Http\RoleController@edit']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\RoleController@destroy', false),'name'=>'Role Delete', 'controller'=>'Role', 'action'=>'Pollob666\Acl\Http\RoleController@destroy']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\RoleController@store', false),'name'=>'Role Save', 'controller'=>'Role', 'action'=>'Pollob666\Acl\Http\RoleController@store']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\RoleController@update', false),'name'=>'Role Update', 'controller'=>'Role', 'action'=>'Pollob666\Acl\Http\RoleController@update']);

        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\ResourceController@index', false),'name'=>'Resource List', 'controller'=>'Resource', 'action'=>'Pollob666\Acl\Http\ResourceController@index']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\ResourceController@create', false),'name'=>'Resource Create', 'controller'=>'Resource', 'action'=>'Pollob666\Acl\Http\ResourceController@create']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\ResourceController@edit', false),'name'=>'Resource Edit', 'controller'=>'Resource', 'action'=>'Pollob666\Acl\Http\ResourceController@edit']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\ResourceController@destroy', false),'name'=>'Resource Delete', 'controller'=>'Resource', 'action'=>'Pollob666\Acl\Http\ResourceController@destroy']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\ResourceController@store', false),'name'=>'Resource Save', 'controller'=>'Resource', 'action'=>'Pollob666\Acl\Http\ResourceController@store']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\ResourceController@update', false),'name'=>'Resource Update', 'controller'=>'Resource', 'action'=>'Pollob666\Acl\Http\ResourceController@update']);
        Resource::create(['resource_id'=>sha1('Pollob666\Acl\Http\ResourceController@reGenerateResource', false),'name'=>'Resource ReGenerateResource', 'controller'=>'Resource', 'action'=>'Pollob666\Acl\Http\ResourceController@reGenerateResource']);
    }

}
