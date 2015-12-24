<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */
namespace Setting;

return array(

    'service_manager' => array(
        'aliases' => array(
            'DbAdapter' => 'Zend\Db\Adapter\Adapter',
        ),
        'invokables' => array(
        ),
        'factories' => array(
            'SettingGateway' => 'Setting\ServiceFactory\SettingGateway',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Setting\Controller\Setting' => 'Setting\Controller\SettingController',
        ),
    ),

    'form_elements' => array(
        'factories' => array(
            'Setting\Form\SettingForm' => 'Setting\ServiceFactory\Form\SettingFormFactory',
        ),
    ),

    'router' => array(
        'routes' => array(
            'rest' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/rest-api',
                    'defaults' => array(
                        'controller' => 'Setting\Controller\Setting',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'setting'   => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/setting[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Setting\Controller\Setting',
                            ),
                        ),
                    ),
                    'setting-prefix'   => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/setting/get-prefix',
                            'defaults' => array(
                                'controller' => 'Setting\Controller\Setting',
                                'action'     => 'get-prefix'
                            ),
                        ),
                    ),
                    'setting-serializer'   => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/setting/get-serializer[/:value]',
                            'constraints' => array(
                                'value'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Setting\Controller\Setting',
                                'action'     => 'get-serializer'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    )
);