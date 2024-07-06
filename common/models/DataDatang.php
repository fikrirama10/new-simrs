<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_datang".
 *
 * @property int $id
 * @property string|null $datang
 */
class DataDatang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_datang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datang'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datang' => 'Datang',
        ];
    }
}
