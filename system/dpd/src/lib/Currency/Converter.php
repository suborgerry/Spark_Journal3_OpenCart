<?php
namespace Ipol\DPD\Currency;

class Converter implements \Ipol\DPD\Currency\ConverterInterface
{
	protected $converter;

	public function __construct($converter) {
		$this->converter = $converter;
	}

    public function convert($amount, $currencyFrom, $currencyTo, $actualDate = false){
		if (!$this->converter) {
			return $amount;
		}

		if ($currencyFrom == $currencyTo) {
			return $amount;
		}

		return $this->converter->convert($amount, $currencyFrom, $currencyTo);
	}
}