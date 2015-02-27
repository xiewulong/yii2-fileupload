<?php
/*!
 * file upload asset
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-fileupload
 * https://raw.githubusercontent.com/xiewulong/yii2-fileupload/master/LICENSE
 * create: 2015/2/27
 * update: 2015/2/27
 * version: 0.0.1
 */

namespace yii\fileupload;

use Yii;
use yii\web\AssetBundle;

class FileuploadAsset extends AssetBundle{

	public $sourcePath = '@yii/fileupload/static';

	public $js = [
		'js/Fileupload.js',
	];

}
