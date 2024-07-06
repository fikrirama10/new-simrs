<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_unit".
 *
 * @property int $id
 * @property string|null $unit
 * @property string|null $keterangan
 */
class UserUnit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit', 'keterangan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unit' => 'Unit',
            'keterangan' => 'Keterangan',
        ];
    }
}
