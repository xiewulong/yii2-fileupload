<?php
/*!
 * yii2 extension - 支付系统 - 支付宝sdk
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-payment
 * https://raw.githubusercontent.com/xiewulong/yii2-payment/master/LICENSE
 * create: 2015/1/10
 * update: 2015/2/17
 * version: 0.0.1
 */

namespace yii\fileupload\apis;

use Yii;

class AliyunOss{

	/**
	 * 构造器
	 * @method __construct
	 * @since 0.0.1
	 * @param {array} $config 参数数组
	 * @return {none}
	 */
	public function __construct($config){
		$this->config = $config;
	}

	/**
	 * 获取类对象
	 * @method sdk
	 * @since 0.0.1
	 * @param {array} $config 参数数组
	 * @return {none}
	 * @example Alipay::sdk($config);
	 */
	public static function sdk($config){
		return new static($config);
	}

	/**
	 * 获取类对象
	 * @method sdk
	 * @since 0.0.1
	 * @param {array} $config 参数数组
	 * @return {none}
	 * @example Alipay::sdk($config);
	 */
	public function test(){
		echo $this->curl('http://diankego-images.oss-cn-shenzhen.aliyuncs.com/', []);
	}

	/**
	 * curl远程获取数据方法
	 * @method curl
	 * @since 0.0.1
	 * @param {string} $url 请求地址
	 * @param {array|string} $data 数据
	 * @return {string}
	 */
	private function curl($url, $data){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($curl, CURLOPT_FILETIME, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 0);
		curl_setopt($curl, CURLOPT_NOSIGNAL, 1);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');


		$header[] = "Content-Type:image/jpeg;charset=UTF-8";
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

$file = '/home/xiewulong/com.diankego/webappManage/web/static/images/logo.png';
$file_size = filesize($file);
$h = fopen($file, 'r');

$accessKeyId = 'JxxNYmHUWnd23dgc';
$accessKeySecret = 'bsviPYFzNEXSno7qqdYNRGadnbHFID';

		curl_setopt($curl, CURLOPT_INFILESIZE, $file_size);
		curl_setopt($curl, CURLOPT_INFILE, $h);
		curl_setopt($curl, CURLOPT_UPLOAD, 1);

		$d = curl_exec($curl);
		curl_close($curl);
		return $d;
	}

}
