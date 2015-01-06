<?php
/**
 * Created by PhpStorm.
 * User: Ian Fosbery
 * Date: 12/18/14
 * Time: 11:30 AM
 */

namespace Nmrkt\CommissionJunction;

use GuzzleHttp\Client as GuzzleClient;
use Nmrkt\CommissionJunction\Plugin\AuthTokenPlugin;

class Client extends GuzzleClient
{
	protected $base_url = "https://{subdomain}.api.cj.com/{version}/";

	public function __construct($auth_token, $subdomain, $version = 'v3')
	{

		parent::__construct([
			'base_url' => [
				$this->base_url, [
					'subdomain' => $subdomain,
					'version' => $version
				]
			]
		]);

		$this->getEmitter()->attach(new AuthTokenPlugin($auth_token));

	}
}
