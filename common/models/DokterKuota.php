<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dokter_kuota".
 *
 * @property int $id
 * @property int|null $iddokter
 * @property int|null $idpoli
 * @property int|null $idhari
 * @property string|null $tgl
 * @property int|null $kuota
 * @property int|null $terdaftar
 * @property int|null $sisa
 * @property int|null $status
 */
class DokterKuota extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dokter_kuota';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iddokter', 'idpoli', 'idhari', 'kuota', 'terdaftar', 'sisa', 'status'], 'integer'],
            [['tgl'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iddokter' => 'Iddokter',
            'idpoli' => 'Idpoli',
            'idhari' => 'Idhari',
            'tgl' => 'Tgl',
            'kuota' => 'Kuota',
            'terdaftar' => 'Terdaftar',
            'sisa' => 'Sisa',
            'status' => 'Status',
        ];
    }
	public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
}
