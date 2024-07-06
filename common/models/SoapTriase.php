<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_triase".
 *
 * @property int $id
 * @property string|null $triase
 * @property string|null $keterangan
 * @property string|null $warna
 */
class SoapTriase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_triase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keterangan'], 'string'],
            [['triase', 'warna'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'triase' => 'Triase',
            'keterangan' => 'Keterangan',
            'warna' => 'Warna',
        ];
    }
}
