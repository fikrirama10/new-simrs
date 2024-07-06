<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_hubungan".
 *
 * @property int $id
 * @property string|null $hubungan
 */
class DataHubungan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_hubungan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hubungan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hubungan' => 'Hubungan',
        ];
    }
}
