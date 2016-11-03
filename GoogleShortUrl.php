<?php
namespace common\components;

use yii\base\component;
use yii\web\HttpException;

/**
 * Goo.gl API Class File
 *
 * @author Odinets Ruslan <https://github.com/Sect0R/>
 * @link https://github.com/Sect0R/
 * @license http://www.yiiframework.com/license/
 *
 */

/**
 * Description:
 * ------------
 * This component allows you to short url by the {@link goo.gl} service.
 * Documentation for Google URL Shortener API: {@link https://developers.google.com/url-shortener/}
 * Register Api Key: {@ling https://console.developers.google.com/apis/}
 *
 * Installation:
 * -------------
 *
 * - create folder components in your yii2
 * - move GoogleShortUrl.php into components folder
 * - add component class to config/main.php configuration file
 * ....
 * 'components' => [
 * 		'shorter' => [
 * 			'class' => 'app\components\GoogleShortUrl',
 * 			'apiKey' => 'xxxxxx', // apikey
 * 		],
 * .......
 * ];
 *
 * -------------
 * Usage:
 * Yii::$app->shorter->shortUrl('http://google.com');
 * Yii::$app->shorter->expandUrl('https://goo.gl/FuAUYl');
 */

class GoogleShortUrl extends Component
{
	/** @var string Current class version */
	const CLASS_VERSION = '1.0.1';

	/** @var string API URL */
	const API_URL = 'https://www.googleapis.com/urlshortener/v1/url';

	/** @var string|null API Key */
	public $apiKey = null;

	/**
	 * Component initializer
	 * @throws HttpException on missing CURL PHP Extension
	 */
	public function init()
	{
		// Make sure we have CURL enabled
		if( ! function_exists('curl_init') ) {
			throw new HttpException(500, 'Sorry, Buy you need to have the CURL extension enabled in order to be able to use this class.');
		}

		parent::init();

		if ( ! $this->apiKey) {
			throw new HttpException(500, 'Set apiKey in config');
		}
	}

	/**
	 * Short url by goo.gl
	 * @param $longUrl
	 * @return mixed
	 * @throws HttpException on missing apiKey
	 */
	public function shortUrl($longUrl) {
		$apiParams = json_encode(['longUrl' => $longUrl]);
		$result = $this->request($apiParams, 1);

		return ( !empty($result['id']) ? $result['id'] : false);
	}

	/**
	 * @param $shortUrl
	 * @return mixed
	 */
	public function expandUrl($shortUrl){
		$result = $this->request(['shortUrl' => $shortUrl]);

		return ( !empty($result['longUrl']) ? $result['longUrl'] : false);
	}

	/**
	 * Make curl request
	 * @param array $data
	 * @param int $post
	 * @return mixed
	 */
	private function request($data = [], $post = 0) {

		$requestUrl = self::API_URL .'?key='. $this->apiKey;

		$curl = curl_init();

		if ( $post ) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		} else {
			$data['key'] = $this->apiKey;
			$requestUrl = self::API_URL .'?'. http_build_query($data);
		}

		curl_setopt($curl, CURLOPT_URL, $requestUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER,['Content-type:application/json']);

		$curl_response = curl_exec($curl);
		$json_decode_response = json_decode($curl_response, TRUE);
		curl_close($curl);

		return $json_decode_response;
	}

}