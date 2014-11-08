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
class epFileNotFoundException extends \Exception
{

    public function __construct($fileName)
    {
        parent::__construct(sprintf('File \'%s\' not found.', $fileName));
    }
}
?>