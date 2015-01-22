<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /**
     * Action to display the wsdl
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function handleAction()
    {
        /* @var $server \Zend\Soap\Server */
        $server = $this->getServiceLocator()->get('SoapServer');
        $server->handle();

        return $this->getResponse();
    }

    /**
     * Return the wsdl
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function wsdlAction()
    {
        $autodiscover = $this->getServiceLocator()->get('SoapAutoDiscover');

        $response = $this->getResponse();
        $response->setContent($autodiscover->toXml());
        $response->getHeaders()->addHeaderLine('Content-type', 'application/xml');

        return $response;
    }

    public function cliAction()
    {
        $adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        $geocoder = new \Geocoder\Provider\GoogleMapsProvider($adapter);

        $from = array_pop($geocoder->getGeocodedData('Rijsenborch 102, Vianen, Netherlands'));
        $to = array_pop($geocoder->getGeocodedData('Van Deventerlaan 30, Utrect, Netherlands'));

        $geotools = new \League\Geotools\Geotools();
        $coordA   = new \League\Geotools\Coordinate\Coordinate(array($from['latitude'], $from['longitude']));
        $coordB   = new \League\Geotools\Coordinate\Coordinate(array($to['latitude'], $to['longitude']));
        $distance = $geotools->distance()->setFrom($coordA)->setTo($coordB);

        echo $distance->in('km')->haversine();
    }
}
