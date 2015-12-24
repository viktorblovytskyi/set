<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */
namespace Setting\ServiceFactory\Form;

use Setting\Form\SettingForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SettingFormFactory implements FactoryInterface {

    /**
     * Create service
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new SettingForm($serviceLocator->getServiceLocator());
    }
}