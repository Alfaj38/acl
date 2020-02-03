<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shanto\Acl\Services;

use Shanto\Acl\Models\Resource;
use Validator;

/**
 * Description of ResourceService
 *
 * @author Shanto Kumar Sarker <sk.bd2007@gmail.com>shanto
 */
class ResourceService {
    /**
     *
     * @param  array  $data
     * @param int $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data, $id=0) {
        if($id){
            $action = 'required|max:90|unique:resources,action,'.$id.',resource_id';
        }else{
            $action = 'required|max:90|unique:resources';
        }
        
        return Validator::make($data, [
            'name' => 'required|max:60',
            'controller' => 'required|max:60',
            'action' => $action,
        ]);
    }
    
    /**
     * 
     * @param array $data
     * @return Resource
     */
    public function create(array $data){
        return Resource::create([
            'resource_id'=>sha1($data['action'], false),
            'name' => $data['name'],
            'controller' => $data['controller'],
            'action' => $data['action']
        ]);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id){
        return Resource::find($id)->update([
            'name' => $data['name'],
            'controller' => $data['controller'],
            'action' => $data['action']
        ]);
    }
}
