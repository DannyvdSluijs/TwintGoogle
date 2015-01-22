<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Application\Service;
/**
 * Description of SoapService
 *
 * @author dannyvandersluijs
 */
class SoapService
{
    private $serviceLocator;

    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     *
     * @param \Application\Soap\Request\DistanceRequest $request
     * @return \Application\Soap\Response\DistanceResponse
     */
    public function Distance(\Application\Soap\Request\DistanceRequest $request)
    {
        $adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        $geocoder = new \Geocoder\Provider\GoogleMapsProvider($adapter);

        $from = array_pop($geocoder->getGeocodedData('Rijsenborch 102, Vianen, Netherlands'));
        $to = array_pop($geocoder->getGeocodedData('Van Deventerlaan 30, Utrect, Netherlands'));

        $geotools = new \League\Geotools\Geotools();
        $coordA   = new \League\Geotools\Coordinate\Coordinate(array($from['latitude'], $from['longitude']));
        $coordB   = new \League\Geotools\Coordinate\Coordinate(array($to['latitude'], $to['longitude']));
        $distance = $geotools->distance()->setFrom($coordA)->setTo($coordB);
        return new \Application\Soap\Response\DistanceResponse($distance->in('km')->haversine());
    }


}
