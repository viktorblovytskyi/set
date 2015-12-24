<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace Setting\ServiceFactory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SettingForm implements FactoryInterface
{

    /**
     * @inheritdoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new \Setting\Form\SettingForm();
        return $form;
    }
}
