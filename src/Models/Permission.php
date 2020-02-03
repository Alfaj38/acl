<?php

namespace Shanto\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * Class Permission
 * @package Shanto\Acl\Models
 * @author Shanto Kumar Sarker <sk.bd2007@gmail.com>
 */
class Permission extends Model {
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     *
     * @var integer
     */
    protected $primaryKey = 'permission_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'resource_id'];

    public static function bulkInsert($data) {
        DB::table('permissions')->insert($data);
    }

    public function scopeRoles($query, $roles){
        return $query->whereIn('role_id', $roles);
    }

    public function scopeRole($query, $role_id) {
        return $query->whereRoleId($role_id);
    }
            
    public function scopeResource($query, $resource_id){
        return $query->whereResourceId($resource_id);
    }
    
    public function resourceItem(){
        return $this->hasOne(Resource::class, 'resource_id', 'resource_id');
    }
}
