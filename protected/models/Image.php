<?php

/**
 * This is the model class for table "image".
 *
 * The followings are the available columns in table 'image':
 * @property integer $id
 * @property integer $user_id
 * @property string $long_id
 * @property string $image_resolution
 * @property string $image_created
 * @property string $image_updated
 * @property string $image_file_type
 * @property integer $image_file_size
 * @property string $image_file_name
 * @property string $image_file_content
 */
class Image extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id, image_file_size', 'numerical', 'integerOnly'=>true),
			array('long_id', 'length', 'max'=>31),
			array('image_resolution', 'length', 'max'=>12),
			array('image_file_type', 'length', 'max'=>63),
			array('image_file_name', 'length', 'max'=>255),
			array('image_created, image_updated, image_file_content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, long_id, image_resolution, image_created, image_updated, image_file_type, image_file_size, image_file_name, image_file_content', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'long_id' => 'Long',
			'image_resolution' => 'Image Resolution',
			'image_created' => 'Image Created',
			'image_updated' => 'Image Updated',
			'image_file_type' => 'Image File Type',
			'image_file_size' => 'Image File Size',
			'image_file_name' => 'Image File Name',
			'image_file_content' => 'Image File Content',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('long_id',$this->long_id,true);
		$criteria->compare('image_resolution',$this->image_resolution,true);
		$criteria->compare('image_created',$this->image_created,true);
		$criteria->compare('image_updated',$this->image_updated,true);
		$criteria->compare('image_file_type',$this->image_file_type,true);
		$criteria->compare('image_file_size',$this->image_file_size);
		$criteria->compare('image_file_name',$this->image_file_name,true);
		$criteria->compare('image_file_content',$this->image_file_content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Image the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function create()
    {
        $model = new static;
        $model->user_id = app()->user->id;
        $model->image_created = Shared::toDatabase(time());
        $model->long_id = Shared::generateRandomPassword(12);

        return $model;
    }

    public function display($width = null, $height = null)
    {
        if (isset($_GET['w']))
            $width = (int) $_GET['w'];
        if (isset($_GET['h']))
            $height = (int) $_GET['h'];

        if ($width == null) {
            $cacheId = 'image-preview-' . $this->id;
        } else {
            $cacheId = 'image-preview-' . $this->id . '-' . $width;
        }

        $path = app()->cache->get($cacheId);
        if ($path !== false) {
            if (file_exists($path)) {
                header("Content-type: $this->image_file_type");
                header("Content-Disposition: imline; filename=$this->image_file_name");
                header("Pragma: public");
                header("Expires: 1200");
                header("Cache-Contril: must-revalidate, post-check=0, pre-check=0");
                readfile($path);
                return true;
            }
        }

        if (strstr($this->image_file_type, 'png')) {
            $type = 'png';
        } elseif (strstr($this->image_file_type, 'gif')) {
            $type = 'gif';
        } else {
            $type = 'jpeg';
        }

        if (strlen($this->image_file_content)) {
            $resample = $this->resize($width,$height);
            if ($this->getCache()) {
                $path = Yii::getPathOfAlias('application.runtime.cache') . DIRECTORY_SEPARATOR . $cacheId . '.' . $type;
                call_user_func('image' . $type, $resample, $path);
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-type: $this->image_file_type");
                header("Content-Disposition: inline; filename=$this->image_file_name");

                $dependency = new CDbCacheDependency('SELECT image_updated FROM image WHERE id=' . $this->id);
                app()->cache->set($cacheId, $path, 0, $dependency);

                readfile($path);
            }
        }
        return false;
    }

    public function resize($width, $height, $originalImage = null)
    {
        if ($originalImage == null)
            $originalImage = imagecreatefromstring($this->image_file_content);

        $oldWidth = imagesx($originalImage);
        $oldHeight = imagesy($originalImage);
        $dontResize = false;

        if ($width && $height) {
            $factorW = (float)$width / (float)$oldWidth;
            $factorH = (float)$height / (float)$oldHeight;
            if ($factorH > $factorW) {
                $height = null;
            } else {
                $width = null;
            }
        }

        if ($width > 0) {
            if ($oldWidth < $width)
                $dontResize = true;
            $factor = (float)$width / (float)$oldWidth;
            $height = (int)($factor * $oldHeight);
        } elseif ($height > 0) {
            if ($oldHeight < $height)
                $dontResize = true;
            $factor = (float)$height / (float)$oldHeight;
            $width = (int)($factor * $oldWidth);
        } else {
            $dontResize = true;
        }

        if ($dontResize) {
            $resampled = $originalImage;
        } else {
            $resampled = imagecreatetruecolor($width, $height);
            imagealphablending($resampled, false);
            imagesavealpha($resampled, true);
            $transparent = imagecolorallocatealpha($resampled, 255, 255, 255, 127);
            imagefilledrectangle($resampled, 0, 0, $width, $height, $transparent);
            imagecopyresampled($resampled, $originalImage, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);
        }
        return $resampled;
    }

    public function getUrl()
    {
        $conf = array(
            'img' => $this->long_id
        );
        $path = app()->createAbsoluteUrl('image/display', $conf);
        return $path;
    }

    public function getWidth()
    {
        $arr = explode('x', $this->image_resolution);
        if (count($arr) == 2) {
            return $arr[0];
        }
        return false;
    }

    public function getHeight()
    {
        $arr = explode('x', $this->image_resolution);
        if (count($arr) == 2) {
            return $arr[1];
        }
        return false;
    }

    private function getCache()
    {
        $cache = app()->getRuntimePath() . DIRECTORY_SEPARATOR . 'cache';
        if (!is_dir($cache)) {
            mkdir($cache,0770,true);
            return true;
        }
        return true;
    }
}
