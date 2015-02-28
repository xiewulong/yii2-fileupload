<?php

namespace yii\fileupload;

use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\imagine\Image;

class FileuploadAction extends Action{

	private $manager;

	private $types = [
		'image' => ['image/gif', 'image/jpeg', 'image/pjpeg'],
	];

	public function init(){
		parent::init();

		$this->manager = \Yii::createObject(Yii::$app->components['fileupload']);
		$size = [230, 500];
		$file = [
			'tmp' => '/home/xiewulong/com.diankego/webappManage/web/assets/upload/2015/02/28/dkg_dev_u_1425060976_cc73fbdf305b49eab7c96316ede93be2.jpg',
			'src' => '/assets/upload/2015/02/28/dkg_dev_u_1425060976_cc73fbdf305b49eab7c96316ede93be2.jpg',
		];
		$_file = $this->manager->addSuf($file, $size);
	}

	public function run(){
		$request = \Yii::$app->request;
		$name = $request->post('name');
		$min = $request->post('min');
		$max = $request->post('max');
		$type = $request->post('type');
		$sizes = $request->post('sizes');
		$response = ['status' => 0, 'message' => \Yii::t('common', 'File upload failed') . ', ' . \Yii::t('common', 'Please try again')];

		if(!empty($name) && !empty($_FILES)){
			$_file = $_FILES[$name];
			if(!empty($min) && $_file['size'] < $min){
				$response['message'] = \Yii::t('common', 'File size too small');
			}else if(!empty($max) && $_file['size'] > $max){
				$response['message'] = \Yii::t('common', 'File size too large');
			}else if(!empty($type) && !in_array($_file['type'], $this->types[$type])){
				$response['message'] = \Yii::t('common', 'Please upload the right file type');
			}else{
				$file = $this->manager->createFile(array_pop(explode('.', $_file['name'])));
				if(move_uploaded_file($_file['tmp_name'], $file['tmp'])){
					$response['status'] = 1;
					$response['message'] = \Yii::t('common', 'File upload successful');
					$response['data'] = ['original' => $file['src']];
					if(!empty($sizes)){
						foreach(explode('|', $sizes) as $size){
							$_size = explode('x', $size);
							if(count($_size) != 2)continue;
							$thumbnail = $this->manager->addSuf($file, $_size);
							Image::thumbnail($file['tmp'], $_size[0], $_size[1], 'inset')->save($thumbnail['tmp']);
							$response['data']['t' . $size] = $thumbnail['src'];
						}
					}
				}
			}
		}

		return '<script type="text/javascript">parent.' . $name . '(' . Json::encode($response) . ');</script>';
	}
	
}