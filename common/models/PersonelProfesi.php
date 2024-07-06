<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "personel_profesi".
 *
 * @property int $id
 * @property string|null $profesi
 */
class PersonelProfesi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personel_profesi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profesi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profesi' => 'Profesi',
        ];
    }
}
