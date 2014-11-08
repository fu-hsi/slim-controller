<?php
/**
 * SlimController - Adds application controller support.
 *
 * @author      Mariusz Kacki
 * @copyright   2014 Mariusz Kacki
 * @package     SlimController
 * @version     1.0.0
 * @link        https://github.com/fu-hsi/slim-controller
 * @license     http://opensource.org/licenses/MIT MIT License 
 */
namespace SlimController;

use SlimController\Exception\epFileNotFoundException;
use SlimController\Exception\epClassNotFoundException;

/**
 *
 * @package SlimController
 * @author Mariusz Kacki
 * @since 1.0.0
 */
class epDispatcher extends epObject
{

    /**
     *
     * @var \Slim\Slim
     */
    private $app;

    /**
     *
     * @var string
     */
    private $defaultController = 'Home';

    /**
     *
     * @var string
     */
    private $defaultAction = 'index';

    /**
     *
     * @param \Slim\Slim $app            
     */
    public function __construct(\Slim\Slim $app)
    {
        parent::__construct();
        $this->app = $app;
        
        if ($controllerName = $app->config('ep.controller.default')) {
            $this->defaultController = $controllerName;
        }
        
        if ($actionName = $app->config('ep.action.default')) {
            $this->defaultAction = $actionName;
        }
    }

    /**
     *
     * @param string $controllerName            
     */
    public function setDefaultController($controllerName)
    {
        $this->defaultController = $controllerName;
    }

    /**
     *
     * @param string $actionName            
     */
    public function setDefaultAction($actionName)
    {
        $this->defaultAction = $actionName;
    }

    /**
     *
     * @param \Slim\Route $route            
     * @throws \LogicException
     * @throws SlimController\Exception\FileNotFoundException
     * @throws SlimController\Exception\ClassNotFoundException
     */
    public function dispatch(\Slim\Route $route)
    {
        $params = $route->getParams();
        $controllerName = ucfirst(strtolower(empty($params['controller']) ? $this->defaultController : $params['controller'])) . 'Controller';
        $fileName = rtrim($this->app->config('ep.controller.path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $controllerName . '.php';
        
        if (file_exists($fileName)) {
            require_once $fileName;
            
            if (class_exists($controllerName, true)) {
                $controller = new $controllerName($this->app);
                
                if ($controller instanceof epController) {
                    $controller->initialize();
                    
                    $actionName = strtolower(empty($params['action']) ? $this->defaultAction : $params['action']) . 'Action';
                    
                    if (is_callable(array(
                        $controller,
                        $actionName
                    ))) {
                        unset($params['controller'], $params['action']);
                        
                        $reflectionMethod = new \ReflectionMethod($controller, $actionName);
                        $reflectionParameters = $reflectionMethod->getParameters();
                        
                        $passedArguments = array();
                        foreach ($reflectionParameters as $reflectionParameter) {
                            $defaultValue = $reflectionParameter->isDefaultValueAvailable() ? $reflectionParameter->getDefaultValue() : null;
                            
                            if (array_key_exists($reflectionParameter->name, $params)) {
                                $passedArguments[] = $params[$reflectionParameter->name];
                            } else {
                                $passedArguments[] = $defaultValue;
                            }
                        }
                        
                        $result = $reflectionMethod->invokeArgs($controller, $passedArguments);
                    } else {
                        $result = $controller->_default();
                    }
                } else {
                    throw new \LogicException('Object must be an subclass of epController class.');
                }
            } else {
                throw new epClassNotFoundException($controllerName);
            }
        } else {
            throw new epFileNotFoundException($fileName);
        }
    }
}

?>