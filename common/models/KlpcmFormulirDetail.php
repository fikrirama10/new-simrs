<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "klpcm_formulir_detail".
 *
 * @property int $id
 * @property string|null $detail
 */
class KlpcmFormulirDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'klpcm_formulir_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detail'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detail' => 'Detail',
        ];
    }
	public static function getOptions(){
		$data=  static::find()->orderBy(['detail'=>SORT_ASC])->all();
		$value=(count($data)==0)? [''=>'']: \yii\helpers\ArrayHelper::map($data, 'detail','detail'); //id = your ID model, name = your caption

		return $value;
	}
}
