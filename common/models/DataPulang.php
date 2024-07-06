<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_pulang".
 *
 * @property int $id
 * @property string|null $pulang
 */
class DataPulang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_pulang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pulang'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pulang' => 'Pulang',
        ];
    }
}
