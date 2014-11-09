Slim Controller
===============
Adds application controller support.

## Example of Home Controller class

```php
<?php
/**
 * Filename: HomeController.php
 */
use SlimController\epController;

class HomeController extends epController
{

    /**
     * Called after constructor.
     */
    public function initialize()
    {
        echo sprintf('%s()<br>', __METHOD__);
    }

    /**
     * Called for URI:
     *   /home
     *   /home/index
     *   /home/index/5
     */
    public function indexAction($id = null)
    {
        echo sprintf('%s(%s)<br>', __METHOD__, $id);
    }

    /**
     * Called for URI:
     *   /home/delete
     *   /home/delete/5
     */
    public function deleteAction($id = null)
    {
        echo sprintf('%s(%s)<br>', __METHOD__, $id);
    }

    /**
     * Called if no action found.
     */
    public function _default()
    {
        echo sprintf('%s()<br>', __METHOD__);
    }
}
?>
```

Each controller class has protected *$app* member which is a Slim application reference.

```php
<?php
use SlimController\epController;

class HomeController extends epController
{

    public function indexAction()
    {
        $this->app->flashNow('info', 'I am an indexAction() method.');
    }
}
?>
```

##Example of Slim application

```php
<?php
use SlimController\epController;
use SlimController\epDispatcher;
use SlimController\Exception;
use Slim\Route;

$loader = require 'vendor/autoload.php';
$loader->add('SlimController', __DIR__ . DIRECTORY_SEPARATOR . 'vendor');

$epConfig = array(
    'ep.controller.path' => __DIR__ . DIRECTORY_SEPARATOR . 'controllers',
    'ep.controller.default' => 'Home',
    'ep.action.default' => 'index'
);

$app = new \Slim\Slim($epConfig);

$app->map('/(:controller(/:action(/:id)))', function () use($app)
{
    $dispatcher = new epDispatcher($app);
    $currentRoute = $app->router()->getCurrentRoute();

    try {
        $dispatcher->dispatch($currentRoute);
        
        echo "Uri: " . $app->router()
            ->urlFor('epRoute', $currentRoute->getParams()) . '<br>';
    } catch (epFileNotFoundException $e) {
        echo "Exception: " . $e->getMessage();
    } catch (epClassNotFoundException $e) {
        echo "Exception: " . $e->getMessage();
    } catch (\Exception $e) {
        echo "Exception: " . $e->getMessage();
    }
})
->via('GET', 'POST')
->conditions(array('id' => '\d+'))
->name('epRoute');

$app->run();
?>
```
