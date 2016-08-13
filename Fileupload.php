<?php
/*!
 * yii - widget - file upload
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-fileupload
 * https://raw.githubusercontent.com/xiewulong/yii2-fileupload/master/LICENSE
 * create: 2016/8/12
 * update: 2016/8/14
 * since: 0.0.2
 */

namespace yii\fileupload;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Fileupload extends Widget {

	public $model;

	public $attribute;

	public $value;

	public $options = [];

	public $hiddenOptions = [];

	public $fileOptions = [];

	public $action;

	public $min;

	public $max;

	public $type;

	public $sizes;

	private $_active;

	private $_name;

	private $_value;

	public function init() {
		parent::init();

		$this->setNameAndValue();
		FileuploadAsset::register($this->getView());
	}

	public function run() {
		return Html::tag('div', $this->hiddenInput . $this->fileInput, $this->options);
	}

	protected function getFileInput() {
		return Html::input('file', null, null, ArrayHelper::merge($this->fileOptions, [
			'data-fileupload' => $this->_name,
			'data-action' => $this->action,
			'data-min' => $this->min,
			'data-max' => $this->max,
			'data-type' => $this->type,
			'data-sizes' => $this->sizes,
			'data-csrf-param' => \Yii::$app->request->csrfParam,
			'data-csrf-token' => \Yii::$app->request->csrfToken,
		]));
	}

	protected function getHiddenInput() {
		return $this->_value ? Html::input('hidden', $this->_name, $this->_value, $this->hiddenOptions) : null;
	}

	private function setNameAndValue() {
		if($this->model) {
			$this->_name = Html::getInputName($this->model, $this->attribute);
			$this->_value = Html::getAttributeValue($this->model, $this->attribute);
			if(!array_key_exists('id', $this->options)) {
				$this->options['id'] = Html::getInputId($this->model, $this->attribute);
			}
		} else {
			$this->_name = $this->attribute;
			$this->_value = $this->value;
		}
	}

}
