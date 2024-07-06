<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_satuan".
 *
 * @property int $id
 * @property string|null $satuan
 */
class DataSatuan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_satuan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['satuan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'satuan' => 'Satuan',
        ];
    }
}
