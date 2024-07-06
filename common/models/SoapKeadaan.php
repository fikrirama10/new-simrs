<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_keadaan".
 *
 * @property int $id
 * @property string|null $keadaan
 */
class SoapKeadaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_keadaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keadaan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keadaan' => 'Keadaan',
        ];
    }
}
