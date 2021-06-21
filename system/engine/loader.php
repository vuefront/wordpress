<?php
final class VFA_Loader
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }
    
    public function resolver($route, $data = array())
    {
            $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
        
            $action = new VFA_Action($route);
            $output = $action->execute($this->registry, array(&$data));
    
            if (!$output instanceof Exception) {
                return $output;
            }
        
    }

    public function model($route)
    {
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
        
        if (!$this->registry->has('model_' . str_replace('/', '_', $route))) {
            $file  = VFA_DIR_PLUGIN . 'model/' . $route . '.php';
            $class = 'VFA_Model' . preg_replace('/[^a-zA-Z0-9]/', '', $route);
            
            if (is_file($file)) {
                include_once($file);
    
                $proxy = new VFA_Proxy();
                
                foreach (get_class_methods($class) as $method) {
                    $proxy->{$method} = $this->callback($this->registry, $route . '/' . $method);
                }

                $this->registry->set('model_' . str_replace('/', '_', (string)$route), $proxy);
            } else {
                throw new \Exception('Error: Could not load model ' . $route . '!');
            }
        }
    }

    	/**
	 * 
	 *
	 * @param	string	$route
	 * @param	array	$data
	 *
	 * @return	string
 	*/
	public function view($route, $data = array()) {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		// Keep the original trigger
		$trigger = $route;
		
		// Template contents. Not the output!
		$code = '';
		
		// Trigger the pre events
        $template = new VFA_Template();
            
        foreach ($data as $key => $value) {
            $template->set($key, $value);
        }

        $output = $template->render(VFA_DIR_PLUGIN . 'view/template/'   . $route, $code);
    
		return $output;
	}

    
    protected function callback($registry, $route)
    {
        return function ($args) use ($registry, $route) {
            static $model;
            
            $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);

            $class = 'VFA_Model' . preg_replace('/[^a-zA-Z0-9]/', '', substr($route, 0, strrpos($route, '/')));
                
            $key = substr($route, 0, strrpos($route, '/'));
                
            if (!isset($model[$key])) {
                $model[$key] = new $class($registry);
            }
                
            $method = substr($route, strrpos($route, '/') + 1);
                
            $callable = array($model[$key], $method);
    
            if (is_callable($callable)) {
                $output = call_user_func_array($callable, $args);
            } else {
                throw new \Exception('Error: Could not call model/' . $route . '!');
            }
            
            return $output;
        };
    }
}
