<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Application\Soap\Response;
/**
 * Description of DistanceResponse
 *
 * @author dannyvandersluijs
 */
class DistanceResponse
{
    public $Distance = 0.0;

    public function __construct($distance)
    {
        $this->Distance = $distance;
    }
}
