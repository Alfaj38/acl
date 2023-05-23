<?php

namespace Pollob666\Acl\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pollob666\Acl\Services\ResourceService;
use Pollob666\Acl\Models\Resource;
use Illuminate\Support\Facades\Route;

class ResourceController extends Controller {
    private $route;
    private $_controller_path_pattern = 'App\\\Http\\\Controllers';
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $rows = new Resource;
        if($q = request('q')){
            $rows = $rows->where('name', 'LIKE', "%{$q}%")
                        ->orWhere('controller', 'LIKE', "%{$q}%")
                        ->orWhere('action', 'LIKE', "%{$q}%");
        }

        return view('acl::resource.index', [
            'rows' => $rows->paginate(30)
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('acl::resource.create');
    }

    /**
     * @param ResourceService $resourceService
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ResourceService $resourceService, Request $request) {
        $validator = $resourceService->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        $resourceService->create($request->all());

        return redirect('/resource')->with('msg', 'Resource created successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id) {
        return view('acl::resource.edit', [
            'id' => $id,
            'resource' => Resource::find($id)
                ]
        );
    }

    /**
     * @param $id
     * @param ResourceService $resourceService
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, ResourceService $resourceService, Request $request) {
        $validator = $resourceService->validator($request->all(), $id);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        $resourceService->update($request->all(), $id);
        return redirect('/resource')->with('msg', 'Resource updated successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        if (Resource::destroy($id)) {
            return redirect('/resource')->with('msg', 'Resource deleted successfully!');
        }

        return redirect('/resource')->with('msg', 'Resource can\'t deleted successfully!');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reGenerateResource(){

        foreach (Route::getRoutes()->getRoutes() as $route)

        {
            $action = $route->getAction();

            if (array_key_exists('controller', $action))
            {
                // You can also use explode('@', $action['controller']); here
                // to separate the class name from the method
                $method = $route->methods();
                $method = $method[0]??'';

                $resource = Resource::where('action', trim($action['controller']))->count();
                if($resource){
                    continue;
                }else{
                    $this->makeResourceMaker($action['controller'],$method);
                }

            }



        }
        return back()->with('msg','Resources has been added');
    }

    /**
     *
     * @param string $action
     * @return string
     */
    private function makeResourceMaker($action, $method){
        $resource_id = sha1($action, false);
        $controller = $this->__getController($action);

        $name = $controller . ' ' . $method .'::'.$this->_getActionName($action);
        $name = $controller . ' ' .$this->_getActionName($action);

        Resource::create(['resource_id'=>$resource_id,'name'=>$name, 'controller'=>$controller, 'action'=>$action]);

    }

    /**
     * @des Namespace will be \Form\RegistrationController will be like Form-Registration
     * @param string $action
     * @return string
     */
    private function _getControllerName($action) {

        $pattern = '/'.$this->_controller_path_pattern.'\\\([a-zA-Z\\\]+)Controller\@/';
        preg_match($pattern, $action, $matches);

        if (count($matches) == 2) {
            return str_replace('\\', '-', $matches[1]);
        }

        return null;
    }

    /**
     * @des Namespace will be \Form\RegistrationController will be like Form-Registration
     * @param string $action
     * @return string
     */
    private function __getController($action) {
        $controller = class_basename($action);
        $controller = explode('@', $controller);
        if($controller[0]??''){
            $controller = str_replace("Controller","",$controller[0]);
        }else{
          return '';
        }
        return $controller;
    }
    /**
     *
     * @param string $action
     * @return string
     */
    private function _getActionName($action) {

        $pattern = '/([a-zA-Z]+)$/';
        preg_match($pattern, $action, $matches);

        if (count($matches) == 2) {
            $name = ucfirst($matches[1]);
            if($name=='Index'){
                $name ='List';
            }else if($name == 'Destroy'){
                $name ='DELETE';
            }else if($name=='Store'){
                $name ='Save';
            }

            return $name;
        }

        return '';
    }
}
