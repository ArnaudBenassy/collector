<?php

namespace AppBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DumbDataCollector extends DataCollector {

	public function __construct()
	{
		$this->data['letters'] = [
			'a' => 0,
			'e' => 0,
			'i' => 0,
			'o' => 0,
			'u' => 0,
			'y' => 0,
			'0' => 0,
			'1' => 0,
			'2' => 0,
			'3' => 0,
			'4' => 0,
			'5' => 0,
			'6' => 0,
			'7' => 0,
			'8' => 0,
			'9' => 0,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function collect(Request $request, Response $response, \Exception $exception = null)
	{
		foreach ($request as $elt) {

			try {
				$hash = strtolower(md5(serialize($elt)));
			} catch (\Exception $e) {
				continue;
			}

			for ($i = 0; $i < strlen($hash); $i++) {
				$letter = $hash[$i];
				if (array_key_exists($letter, $this->data['letters'])) {
					$this->data['letters'][$letter] += 1;
				}
			}

		}
	}

	public function getGlobalCount()
	{
		return array_sum($this->data['letters']);
	}

	public function getLetters()
	{
		return $this->data['letters'];
	}

	public function getName()
	{
		return 'dumb';
	}

}
