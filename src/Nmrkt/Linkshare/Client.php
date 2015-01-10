<?php
/**
 * Created by PhpStorm.
 * User: Ian Fosbery
 * Date: 12/18/14
 * Time: 11:30 AM
 */

namespace Nmrkt\Linkshare;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Event\SubscriberInterface;
use Nmrkt\GuzzleOAuth2\GrantType\ClientCredentials;
use Nmrkt\GuzzleOAuth2\OAuth2Subscriber;

class Client extends GuzzleClient
{
	/**
	 * @var Client - the client making the oauth requests
	 */
	private $oauth_client;

	/**
	 * @var GrantType - the Oauth Grant Type Class
	 */
	private $oauth_grant_type;

	/**
	 * @var	SubscriberInterface - The Client Subscriber To Make OAuth Calls
	 */
	private $oauth_subscriber;

	/**
	 * @var Array - array of oauth configs
	 *
	 * EG:
	 * $config = [
	 *		'username' => 'nmrkt',
	 *		'password' => 'password',
	 *		'client_id' => 'your client id',
	 *		'client_secret' => 'your client secret',
	 *		'scope' => 'your scope(s)', // optional
	 *	];
	 */
	private $oauth_config;

	protected $oauth_url = 'https://api.rakutenmarketing.com/token';

	protected $base_url = 'https://api.rakutenmarketing.com/{api_uri}/{version}/';

	protected $api_version = '1.0';

	/**
	 * @param string $api_uri - the uri for the api endpoint, eg "events", or "advancedreports"
	 * @param array $config - your config params.
	 * @param string $version
	 */
	public function __construct($api_uri, $config = [], $version = '1.0')
	{
		$config_default = [
	 		'username' => null,
	 		'password' => null,
	 		'client_id' => null,
	 		'client_secret' => null,
	 		'scope' => null
	 	];
		$this->oauth_config = array_replace($config_default, $config);

		parent::__construct([
			'base_url' => [
				$this->base_url, [
					'api_uri' => $api_uri,
					'version' => $version
				]
			]
		]);

	}

	public function getAuthorizationUrl()
	{
		return $this->oauth_url;
	}

	public function getOauthConfig()
	{
		return $this->oauth_config;
	}

	public function getOauth2Client()
	{
		if (!is_a($this->oauth_client, 'GuzzleHttp\Client')) {
			$this->oauth_client =  new GuzzleClient(['base_url' => $this->oauth_url]);
		}

		return $this->oauth_client;
	}

	public function getClientCredentialsGrantType()
	{
		if (!is_a($this->oauth_grant_type, 'Nmrkt\GuzzleOAuth2\GrantType\ClientCredentials')) {
			$oauth_client = $this->getOauth2Client();
			$this->oauth_grant_type = new ClientCredentials($oauth_client, $this->oauth_config);
		}

		return $this->oauth_grant_type;
	}

	public function getOauth2Subscriber()
	{
		if (!is_a($this->oauth_subscriber, 'Nmrkt\GuzzleOAuth2\OAuth2Subscriber')) {
			$grant_type = $this->getClientCredentialsGrantType();
			$oauth_subscriber = new OAuth2Subscriber($grant_type);
		}

		return $oauth_subscriber;
	}

	public function attachOauth2Subscriber($oauth_subscriber)
	{
		$this->getEmitter()->attach($oauth_subscriber);
	}
}
