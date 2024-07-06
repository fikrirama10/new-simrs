<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_jenjangusia".
 *
 * @property int $id
 * @property string|null $jenjang
 */
class DataJenjangusia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_jenjangusia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenjang'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenjang' => 'Jenjang',
        ];
    }
}
