<?php

/**
 * TopDeliveryApi
 *
 * This class is TopDelivery (http://www.topdelivery.ru/) API wrapper.
 * php-soap is needed
 *
 * Usage example:
 * $top = new TopDeliveryApi('login', 'password', true);
 * $res = $top->getCitiesRegions(33);
 * var_dump($res);
 * As you can see "auth" block is skipped and first-level-parameters names are passed as plain arguments
 *
 * Methods list and parameters description - http://docs.is.topdelivery.ru/
 *
 *
 */

class TopDeliveryApi
{
	const WSDL = 'http://is.topdelivery.ru/api/soap/w/2.0/?WSDL';
	const GATE_LOGIN = 'tdsoap';
	const GATE_PASSWORD = 'fc7a00f11c1bfa9f5b69e0be9117738e';
	const WSDL_TEST = 'http://test.is.topdelivery.ru/api/soap/w/2.0/?WSDL';
	const GATE_LOGIN_TEST = 'tdsoap';
	const GATE_PASSWORD_TEST = '5f3b5023270883afb9ead456c8985ba8';
	const LOGIN_TEST = 'webshop';
	const PASSWORD_TEST = 'pass';

	const PARAM_NAMES = array(
		'addOrders' => array('addedOrders'),
		'addShipment' => array('addedShipmentInfo'),
		'setShipmentOnTheWay' => array('shipmentId'),
		'addShipmentOrders' => array('shipmentOrders'),
		'deleteOrdersFromShipment' => array('shipmentOrders'),
		'getShipmentsInfo' => array('shipmentId'),
		'getCitiesRegions' => array('regionId'),
		'getOrdersInfo' => array('order'),
		'calcOrderCosts' => array('orderParams'),
		'getNearDeliveryDatesIntervals' => array('addressDeliveryProperties'),
		'deleteOrder' => array('orderIdentity'),
		'getShipmentsByParams' => array('pickupAddress', 'shipmentStatus', 'shipmentDirection', 'dateCreate'),
		'getOrdersByParams' => array('orderIdentity', 'orderStatus', 'orderWorkStatus', 'pickupAddress', 'clientInfo', 'reportId', 'dateCreate', 'currentShipment', 'clientPaid'),
		'getReports' => array('date_create'),
		'getPickupAddressesByParams' => array('params')
	);

	private $auth;
	private $client;

	public function __construct($login = '', $password = '', $test = false)
	{

		$wsdl = $test ? self::WSDL_TEST : self::WSDL;
		$login = $test ? self::LOGIN_TEST : $login;
		$password = $test ? self::PASSWORD_TEST : $password;
		$gate_login = $test ? self::GATE_LOGIN_TEST : self::GATE_LOGIN;
		$gate_password = $test ? self::GATE_PASSWORD_TEST : self::GATE_PASSWORD;

		$this->auth = array(
			'login' => $login,
			'password' => $password
		);
		$this->client = new SoapClient($wsdl, array(
			'login' => $gate_login,
			'password' => $gate_password
		));
	}

	public function __call($name, $arguments = array())
	{
		$params = array();
		foreach ($arguments as $index => $argument) {
			$params[self::PARAM_NAMES[$name][$index]] = $argument;
		}
		$params = array_merge(array(
			'auth' => $this->auth
		), $params);

		return $this->client->__soapCall($name, array($name => $params));
	}

}


