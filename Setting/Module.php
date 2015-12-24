<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace Setting;

use Application\Auth\AuthorizationProvider;
use Application\Response\Exception;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');
        /** @var \Zend\EventManager\SharedEventManager $sharedEvents */
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractRestfulController', MvcEvent::EVENT_DISPATCH, array($this, 'testPermission'), 998);
    }


    /**
     * Проверка прав пользователя
     * @param MvcEvent $e
     * @throws Exception в случае отсутствия прав на действие
     */
    public function testPermission(MvcEvent $e)
    {
        $object = $e->getTarget()->getServiceEntity()->getEntityClassName();
        $action = $e->getRouteMatch()->getParam('action');
        if (!$action) {
            $action = strtolower($e->getRequest()->getMethod());
            if ($action == 'get' && !$e->getRouteMatch()->getParam('id')) {
                $action .= 'List';
            }
        }
        
        /** @var AuthorizationProvider $authorizationProvider */
        $authorizationProvider = $e->getApplication()->getServiceManager()->get('AuthorizationProvider');
        if (!$authorizationProvider->checkPermission($object, $action)) {
            throw new Exception('Operation is not permitted', 401);
        }
    }
}
