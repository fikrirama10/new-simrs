<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_farmasi_jenis".
 *
 * @property int $id
 * @property string|null $jenis
 */
class ObatFarmasiJenis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_farmasi_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis' => 'Jenis',
        ];
    }
}
