<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tarif_rincian".
 *
 * @property int $id
 * @property string|null $nama_rincian
 * @property string|null $keterangan
 */
class TarifRincian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarif_rincian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keterangan'], 'string'],
            [['nama_rincian'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_rincian' => 'Nama Rincian',
            'keterangan' => 'Keterangan',
        ];
    }
}
