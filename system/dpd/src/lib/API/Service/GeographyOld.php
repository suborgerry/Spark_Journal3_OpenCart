<?php
namespace Ipol\DPD\API\Service;

use \Ipol\DPD\API\User\UserInterface;
use \Ipol\DPD\API\Client\Factory as ClientFactory;

/**
 * Служба по работе с географическими данными (старая версия API)
 */
class GeographyOld implements ServiceInterface
{
	protected $wdsl = 'http://ws.dpd.ru/services/geography?wsdl';

	protected $clientOld;

	/**
     * Конструктор класса
     * 
     * @param \Ipol\DPD\API\User\UserInterface
     */
	public function __construct(UserInterface $user)
	{
		// По не известным причинам данный сервис в тестовом режиме
		// выдает soap ошибку. В боевом - этой ошибки нет. 
		// Поэтому для этого сервиса мы отключаем тестовый режим всегда
		// $user = new User($user->getClientNumber(), $user->getSecretKey(), false);

		$this->client = ClientFactory::create($this->wdsl, $user);
	}

	/**
	 * Возвращает список городов с возможностью доставки наложенным платежом
	 * 
	 * @param string $countryCode код страны
	 * 
	 * @return array
	 */
	public function getCitiesCashPay($countryCode = 'RU')
	{
		return $this->client->invoke('getCitiesCashPay', array(
			'countryCode' => $countryCode
		), 'request', 'cityCode');
	}

	/**
	 * Возвращает список пунктов приема/выдачи посылок, имеющих ограничения по габаритам и весу, 
	 * с указанием режима работы пункта и доступностью выполнения самопривоза/самовывоза.
	 * При работе с  методом  необходимо проводить получение информации по списку подразделений ежедневно.
	 * 
	 * @param string $countryCode код страны
	 * @param string $regionCode  код региона
	 * @param string $cityCode    код города
	 * @param string $cityName    название города
	 * 
	 * @return array
	 */
	public function getParcelShops($countryCode = 'RU', $regionCode = false, $cityCode = false, $cityName = false)
	{
		$ret = $this->client->invoke('getParcelShops', array_filter(array(
			'countryCode' => $countryCode,
			'regionCode'  => $regionCode,
			'cityCode'    => $cityCode,
			'cityName'    => $cityName
		)));

		return $ret ? $ret['PARCEL_SHOP'] : $ret;
	}

	/**
	 * Возвращает список подразделений DPD, не имеющих ограничений по габаритам и весу посылок приема/выдачи
	 *
	 * @return array
	 */
	public function getTerminalsSelfDelivery2()
	{
		$ret = $this->client->invoke('getTerminalsSelfDelivery2', array(), false);

		return $ret ? $ret['TERMINAL'] : $ret;
	}

	/**
	 * Возвращает информацию о сроке бесплатного хранения на пункте
	 *
	 * @param array $terminalCodes
	 * @param array $serviceCode
	 * 
	 * @return array
	 */
	public function getStoragePeriod(array $terminalCodes = array(), array $serviceCode = array())
	{
		return $this->client->invoke('getStoragePeriod', array_filter(array(
			'terminalCоdes' => implode(',', $terminalCоdes),
			'serviceCode'   => implode(',', $serviceCode)
		)));
	}
}