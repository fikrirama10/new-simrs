<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_hambatan".
 *
 * @property int $id
 * @property string $hambatan
 */
class DataHambatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_hambatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hambatan'], 'required'],
            [['hambatan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hambatan' => 'Hambatan',
        ];
    }
}
