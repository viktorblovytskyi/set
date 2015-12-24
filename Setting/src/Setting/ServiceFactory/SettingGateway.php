<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace Setting\ServiceFactory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Setting\Repository\TableGateway\Setting;

class SettingGateway implements FactoryInterface
{
    
    /**
     * @inheritdoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Setting(
            $serviceLocator->get('DbAdapter')
        );
    }
}
