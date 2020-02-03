<?php

namespace Shanto\Acl\Services;

use Shanto\Acl\Models\Permission;
use Shanto\Acl\Models\UserRole;
use Auth;
use Illuminate\Support\Arr;

/**
 * Description of PermissionCheckService
 *
 * @author Shanto Kumar Sarker <sk.bd2007@gmail.com>shanto
 */
class PermissionCheckService {
        
    private static $_roles=null;
    private static $_resources = [];
    private static $_permission_rows = [];
    private static $_resource_group = [];

    /**
     *
     * @param type $action
     * @param type $user
     * @return boolean
     */
    public static function canAccess($action, $user){
        $roles = self::_getUserRoles($user->{$user->getKeyName()});
        return Permission::resource(sha1($action, false))->roles($roles)->exists();
    }

    /**
     *
     * @param int $userId
     * @return array
     */
    private static function _getUserRoles($userId){
        if(!self::$_roles){
            self::$_roles = Arr::flatten(UserRole::Where('user_id', $userId)->get(['role_id'])->toArray());
        }
        return self::$_roles;
    }

    /**
     *
     * @param string $action
     * @param bool $isActionFullPath
     * @return boolean
     * @example
     * <code>
     * hasAccess('UserController@getIndex')
     * hasAccess('Form\RegistrationController@getIndex')
     * </code>
     */
    public static function hasAccess($action, $isActionFullPath=false) {
        if($isActionFullPath){
            return in_array($action, self::getResources());
        }
        $prefix = config('acl.controller_namespace_prefix', 'App\Http\Controllers');
        return in_array($prefix .'\\'. $action, self::getResources());
    }

    public static function getResources() {
        if (count(self::$_resources) == 0) {
            self::_computeResource();
        }

        return self::$_resources;
    }

    private static function _getPermissionRows() {
        if (count(self::$_permission_rows) == 0 && Auth::user()) {
            $roles = self::_getUserRoles(Auth::id());
            self::$_permission_rows = Permission::with('resourceItem')->roles($roles)->get();
        }

        return self::$_permission_rows;
    }

    /**
     *
     * @param mix $group
     * @return boolean
     */
    public static function hasGroupAccess($group) {
        if (is_array($group)) {
            $resources = self::getResourceGroup();
            foreach ($group as $g) {
                if (in_array($g, $resources)) {
                    return true;
                }
            }
        } else {
            return in_array($group, self::getResourceGroup());
        }

        return false;
    }

    public static function getResourceGroup() {
        if (count(self::$_resource_group) == 0) {
            self::_computeResource();
        }

        return self::$_resource_group;
    }

    private static function _computeResource(){
        $rows = self::_getPermissionRows();
        foreach ($rows as $r) {
            if($r->resourceItem){
                self::$_resource_group[] = $r->resourceItem->controller;
                self::$_resources[] = $r->resourceItem->action;
            }
        }

        self::$_resource_group = array_unique(self::$_resource_group);
    }

}
