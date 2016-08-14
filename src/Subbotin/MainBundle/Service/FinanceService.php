<?php

namespace Subbotin\MainBundle\Service;

use Symfony\Component\Validator\Constraints\DateTime;

class FinanceService
{
	private $url = "https://query2.finance.yahoo.com/v7/finance/chart/:activeName?period2=1470734216&period1=1218446216&interval=1d&indicators=quote&includeTimestamps=true&includePrePost=true&events=div%7Csplit%7Cearn&corsDomain=finance.yahoo.com";
	private $share;

    /**
     * Подгружаем данные с сайта finance.yahoo.com
     * @return mixed
     */
	public function getJsonData()
	{
		$url = str_replace(":activeName", $this->share, $this->url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		$headers = array();
		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		$headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$contents = curl_exec ($ch);
		$contents = mb_convert_encoding($contents, "UTF-8");

		return json_decode($contents);
	}

	/**
     * Задать код акции
	 * @param mixed $share
	 */
	public function setShare($share)
	{
		$this->share = $share;
	}

	/**
     * Получить заданный код акции
	 * @return mixed
	 */
	public function getShare()
	{
		return $this->share;
	}
}