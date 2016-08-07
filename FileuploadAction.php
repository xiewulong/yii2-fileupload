<?php
/*!
 * yii - action - file upload
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-fileupload
 * https://raw.githubusercontent.com/xiewulong/yii2-fileupload/master/LICENSE
 * create: 2015/2/27
 * update: 2016/8/7
 * siace: 0.0.1
 */

namespace yii\fileupload;

use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\imagine\Image;

class FileuploadAction extends Action {

	public $defaultComponent = 'fileupload';

	private $manager;

	private $types = [
		'image' => [
			'image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png',
		],
		'file' => [
			'application/octet-stream',
			'application/msword',
			'application/x-xls',
			'application/vnd.ms-excel',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		],
	];

	public function init() {
		parent::init();

		$this->manager = \Yii::createObject(\Yii::$app->components[$this->defaultComponent]);
	}

	public function run() {
		$request = \Yii::$app->request;
		$name = $request->post('name');
		$min = $request->post('min');
		$max = $request->post('max');
		$type = $request->post('type');
		$sizes = $request->post('sizes');
		$oss = $request->post('oss', 'images');
		$response = ['status' => 0, 'message' => \Yii::t('common', 'File upload failed') . ', ' . \Yii::t('common', 'Please try again')];

		if(!empty($name) && !empty($_FILES)) {
			$_file = $_FILES[$name];
			if(!empty($min) && $_file['size'] < $min) {
				$response['message'] = \Yii::t('common', 'File size too small');
			} else if(!empty($max) && $_file['size'] > $max) {
				$response['message'] = \Yii::t('common', 'File size too large');
			} else if(!empty($type) && !in_array($_file['type'], $this->types[$type])) {
				$response['message'] = \Yii::t('common', 'Please upload the right file type');
			} else {
				$manager = $this->manager;
				$file = $manager->createFile(array_pop(explode('.', $_file['name'])));
				if(move_uploaded_file($_file['tmp_name'], $file['tmp'])) {
					if($type == 'image' && !empty($sizes)) {
						foreach(explode('|', $sizes) as $size) {
							$_size = explode('x', $size);
							if(count($_size) != 2)continue;
							$thumbnail = $manager->addSuf($file, $_size);
							Image::thumbnail($file['tmp'], $_size[0], $_size[1], 'inset')->save($thumbnail['tmp']);
							$response['data']['t' . $size] = $manager->finalFile($thumbnail, $oss);
						}
					}
					$response['status'] = 1;
					$response['message'] = \Yii::t('common', 'File upload successful');
					$response['data']['original'] = $manager->finalFile($file, $oss);
				}
			}
		}

		return '<script type="text/javascript">parent.' . $name . '(' . Json::encode($response) . ');</script>';
	}

}
