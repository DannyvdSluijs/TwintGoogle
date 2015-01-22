<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/soap/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'handle',
                    ),
                ),
            ),
            'wsdl' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/soap/wsdl/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'wsdl',
                    )
                )
            )
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'distance' => array(
                    'options' => array(
                        'route' => 'distance',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'cli',
                        ),
                    ),
                ),
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            'WebService' => function ($sm) {
                $service = new \Application\Service\SoapService($sm);

                return $service;
            },
            'SoapServer' => function ($sm) {
                $config = $sm->get('config');
                $url = $config['soap']['server']['wsdl'];
                $server = new \Zend\Soap\Server($url, array('cache_wsdl' => WSDL_CACHE_NONE));
                $server
                    ->setClass('\Application\Service\SoapService')
                    ->setObject($sm->get('WebService'))
                    ->setClassmap(array(
                        'DistanceRequest' => '\Application\Soap\Request\DistanceRequest',
                        'DistanceResponse' => '\Application\Soap\Response\DistanceResponse'
                    ));

                return $server;
            },
            'SoapAutoDiscover' => function ($sm) {
                $config = $sm->get('config');
                $url = $config['soap']['server']['soap'];
                $autodiscover = new \Zend\Soap\AutoDiscover();
                $autodiscover
                    ->setClass('\Application\Service\SoapService')
                    ->setUri($url);

                return $autodiscover;
            }
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
