<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ruangan_gender".
 *
 * @property int $id
 * @property string|null $gender
 */
class RuanganGender extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ruangan_gender';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gender' => 'Gender',
        ];
    }
}
