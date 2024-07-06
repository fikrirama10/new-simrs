<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "personel_pangkat".
 *
 * @property int $id
 * @property string|null $pangkat
 */
class PersonelPangkat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personel_pangkat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pangkat'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pangkat' => 'Pangkat',
        ];
    }
}
