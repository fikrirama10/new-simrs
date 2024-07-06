<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_kelahiran".
 *
 * @property int $id
 * @property string|null $kelahiran
 */
class DataKelahiran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_kelahiran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kelahiran'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kelahiran' => 'Kelahiran',
        ];
    }
}
