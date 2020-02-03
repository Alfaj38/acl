<?php

namespace Shanto\Acl\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;
use Shanto\Acl\Models\Permission;
use Shanto\Acl\Models\Resource;
use Log;
use Shanto\Acl\Models\Role;
use Shanto\Acl\Services\AnnotationService;


class AclResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically make resources for Uzzal\ACL library';

    protected $_skip=[];
    private $_controller_path_pattern = '';
    private $_roles=[];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $prefix = config('acl.controller_namespace_prefix', 'App\Http\Controllers');
        $this->_skip[] = $prefix.'\Auth';
        $this->_controller_path_pattern = str_replace('\\', '\\\\\\', $prefix);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $l = Route::getRoutes();
        $this->_roles = $this->_getRoles();

        $bar = $this->output->createProgressBar(count($l));
        $bar->setFormat('%percent:3s%% %message%');
        foreach($l->getIterator() as $v){
            $action = $v->getActionName();
            if($action=='Closure' || $this->_skipAction($action)){
                $bar->advance();
                continue;
            }
            $this->_create($action, current($v->methods()), $bar);
            $bar->advance();
        }
        $bar->finish();
    }

    protected function _create($action, $method, $bar){
        $resource_id = sha1($action, false);
        $controller = $this->_getControllerName($action);
        $annotation = new AnnotationService($action);
        $name = $annotation->getResource();
        $allowRoleId = $this->_getAllowRoleId($annotation->getAllowRole());

        if(!$name){
            $name = $controller . ' ' .$method.'::'.$this->_getActionName($action);
        }

        if ($controller) {
            $resource = Resource::find($resource_id);
            if (!$resource && $name != 'Method') {
                Resource::create([
                    'resource_id' => $resource_id,
                    'name' => $name,
                    'controller' => $controller,
                    'action' => $action
                ]);

                $this->_assignPermission($resource_id, $allowRoleId);
                $bar->setMessage($action.' (created)');
            }else{
                if(count($allowRoleId)>0){
                    $this->_updatePermission($resource_id, $allowRoleId);
                }
                if($name==$resource->name){
                    $bar->setMessage($action.' (already exists!)');
                }else{
                    $resource->update([
                        'name' => $name
                    ]);
                    $bar->setMessage($action.' (updated!)');
                }
            }
        }else{
            $bar->setMessage($action.' (skipped!)');
        }
    }

    private function _assignPermission($resource_id, $roles){
        $this->_createPermission(1, $resource_id);
        foreach($roles as $r){
            $this->_createPermission($r, $resource_id);
        }
    }

    private function _createPermission($role_id, $resource_id){
        try{
            Permission::create(['role_id' => $role_id, 'resource_id' => $resource_id]);
        }catch (QueryException $e){}
    }

    private function _getExistingRoleIds($resource_id){
        return Permission::where('resource_id', $resource_id)
            ->select(['role_id'])
            ->get()
            ->map(function($v){
                return $v->role_id;
            })
            ->reject(function($v){
                return $v==1;
            })
            ->toArray();
    }

    private function _updatePermission($resource_id, $roles){
        $existing_roles = $this->_getExistingRoleIds($resource_id);
        @sort($roles);
        @sort($existing_roles);
        if($existing_roles==$roles){
            return true;
        }
        $create = array_diff($roles, $existing_roles);
        $delete = array_diff($existing_roles, $roles);

        Permission::where('resource_id', $resource_id)->whereIn('role_id', $delete)->delete();
        foreach($create as $c){
            Permission::create(['role_id' => $c, 'resource_id' => $resource_id]);
        }
    }

    /**
     * @param string $allowRole
     * @return array
     */
    private function _getAllowRoleId($allowRole){
        if(!$allowRole){
            return [];
        }
        return collect(explode(',', $allowRole))
            ->map(function($i){
                return strtolower(trim($i));
            })
            ->reject(function($v){
                return $v=='developer' || !array_key_exists($v, $this->_roles);
            })
            ->map(function($v){
                return $this->_roles[$v];
            })
            ->toArray();
    }

    private function _getRoles(){
        return Role::select(['role_id', 'name'])->get()->mapWithKeys(function($item){
            return [strtolower($item['name']) => $item['role_id']];
        })->toArray();
    }

    protected function _skipAction($action){
        foreach($this->_skip as $r){
            if(strstr($action, $r)){
                return true;
            }
        }
        return false;
    }

    public function controllerName($action){
        return $this->_getControllerName($action);
    }

    /**
     * @des Namespace will be \Form\RegistrationController will be like Form-Registration
     * @param string $action
     * @return string
     */
    private function _getControllerName($action) {
        $patterns[] = '/'.$this->_controller_path_pattern.'\\\([a-zA-Z\\\]+)Controller\@/';
        $patterns[] = '/Uzzal\\\Acl\\\Http\\\([a-zA-Z\\\]+)Controller\@/';

        foreach($patterns as $p){
            preg_match($p, $action, $matches);
            if (count($matches) == 2) {
                return str_replace('\\', '-', $matches[1]);
            }
        }

        return null;
    }

    /**
     *
     * @param type $action
     * @return string
     */
    private function _getActionName($action) {
        $pattern = '/([a-zA-Z]+)$/';
        preg_match($pattern, $action, $matches);

        if (count($matches) == 2) {
            return ucfirst($matches[1]);
        }

        return '';
    }
}
