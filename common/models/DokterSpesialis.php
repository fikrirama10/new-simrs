<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dokter_spesialis".
 *
 * @property int $id
 * @property string|null $spesialis
 * @property string|null $ker
 */
class DokterSpesialis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dokter_spesialis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ker'], 'string'],
            [['spesialis'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'spesialis' => 'Spesialis',
            'ker' => 'Ker',
        ];
    }
}
