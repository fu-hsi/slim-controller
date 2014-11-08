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

/**
 *
 * @package SlimController
 * @author Mariusz Kacki
 * @since 1.0.0
 */
abstract class epController extends epObject
{

    /**
     *
     * @var \Slim\Slim;
     */
    protected $app;

    /**
     *
     * @param \Slim\Slim $app            
     */
    public function __construct(\Slim\Slim $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    /**
     * Called after constructor.
     */
    public function initialize()
    {}

    /**
     * Called if no action found.
     */
    public function _default()
    {}
}

?>