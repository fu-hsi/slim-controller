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
namespace SlimController\Exception;

/**
 *
 * @package SlimController
 * @author Mariusz Kacki
 * @since 1.0.0
 */
class epClassNotFoundException extends \Exception
{

    public function __construct($className)
    {
        parent::__construct(sprintf('Class \'%s\' not found.', $className));
    }
}
?>