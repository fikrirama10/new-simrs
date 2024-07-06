<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unit_ruangan".
 *
 * @property int $id
 * @property string|null $ruangan
 * @property string|null $ket
 * @property int|null $urutan
 */
class UnitRuangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit_ruangan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan'], 'integer'],
            [['ruangan', 'ket'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ruangan' => 'Ruangan',
            'ket' => 'Ket',
            'urutan' => 'Urutan',
        ];
    }
}
