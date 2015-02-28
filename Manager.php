<?php
/*!
 * yii2 extension - file upload
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-fileupload
 * https://raw.githubusercontent.com/xiewulong/yii2-fileupload/master/LICENSE
 * create: 2015/2/28
 * update: 2015/2/28
 * version: 0.0.1
 */

namespace yii\fileupload;

use Yii;

class Manager{

	//文件名前缀
	public $pre = 'u_';

	//缓存路径配置
	public $tmp = '@webroot/assets/upload';

	//访问路径
	public $src = '@web/assets/upload';

	//存放路径
	private $path = false;

	//文件名
	private $name = false;

	//绝对路径
	private $root = false;

	//创建时间
	private $time = false;

	//时间路径, 精确到天
	public $timepath = false;

	//扩展名
	private $ext;

	//后缀
	private $suf;

	/**
	 * 添加后缀
	 * @method addSuf
	 * @since 0.0.1
	 * @param {string|array} $file 文件名
	 * @param {string|array} $size 尺寸
	 * @return {array}
	 * @example Yii::$app->fileupload->addSuf($file, $suf);
	 */
	public function addSuf($file, $suf){
		if(is_array($suf)){
			$suf = implode('_', $suf);
		}
		if(is_array($file)){
			foreach($file as $type => $path){
				$file[$type] = $this->createSuf($path, $suf);
			}
		}else{
			$file = $this->createSuf($file, $suf);
		}

		return $file;
	}

	/**
	 * 创建后缀
	 * @method addSuf
	 * @since 0.0.1
	 * @param {string} $path 路径
	 * @param {string} $suf 后缀
	 * @return {string}
	 */
	private function createSuf($path, $suf){
		$path = explode('.', $path);
		$ext = array_pop($path);
		$path[count($path) - 1] .= '_' . $suf;
		$path[] = $ext;
		
		return implode('.', $path);
	}

	/**
	 * 生成文件信息
	 * @method createFile
	 * @since 0.0.1
	 * @param {string} $ext 扩展名
	 * @param {string} [$suf=null] 后缀
	 * @return {array}
	 * @example Yii::$app->fileupload->createFile($ext, $suf);
	 */
	public function createFile($ext, $suf = null){
		$this->ext = $ext;
		if(!empty($suf)){
			$this->suf = $suf;
		}

		return [
			'tmp' => $this->getTmp(),
			'src' => $this->getSrc(),
		];
	}

	/**
	 * 获取网络路径
	 * @method getSrc
	 * @since 0.0.1
	 * @return {string}
	 */
	private function getSrc(){
		return \Yii::getAlias($this->src) . DIRECTORY_SEPARATOR . $this->getPath() . DIRECTORY_SEPARATOR . $this->getName();
	}

	/**
	 * 获取绝对路径
	 * @method getTmp
	 * @since 0.0.1
	 * @return {string}
	 */
	private function getTmp(){
		if($this->root === false){
			$this->root = \Yii::getAlias($this->tmp) . DIRECTORY_SEPARATOR . $this->getPath();
			if(!file_exists($this->root)){
				mkdir($this->root, 0777, true);
			}
		}

		return $this->root . DIRECTORY_SEPARATOR . $this->getName();
	}

	/**
	 * 生成文件名
	 * @method getName
	 * @since 0.0.1
	 * @return {string}
	 */
	private function getName(){
		if($this->name === false){
			$this->name = $this->pre . $this->getTime() . '_' . md5(mt_rand()) . $this->suf . '.' . $this->ext;
		}
		return $this->name;
	}

	/**
	 * 获取存放路径
	 * @method getPath
	 * @since 0.0.1
	 * @return {string}
	 */
	private function getPath(){
		if($this->path === false){
			$this->path = $this->timepath ? date('Y/m/d', $this->getTime()) : '';
		}

		return $this->path;
	}

	/**
	 * 获取创建时间
	 * @method getTime
	 * @since 0.0.1
	 * @return {timestamp}
	 */
	private function getTime(){
		if($this->time === false){
			$this->time = time();
		}

		return $this->time;
	}

}
