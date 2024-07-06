<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_racik_detail".
 *
 * @property int $id
 * @property int|null $idobat
 * @property int|null $idracik
 * @property int|null $idresep
 * @property string|null $nama_obat
 * @property float|null $jumlah
 */
class ObatRacikDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_racik_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idobat', 'idracik', 'idresep'], 'integer'],
            [['jumlah'], 'number'],
            [['nama_obat'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idobat' => 'Idobat',
            'idracik' => 'Idracik',
            'idresep' => 'Idresep',
            'nama_obat' => 'Nama Obat',
            'jumlah' => 'Jumlah',
        ];
    }
}
