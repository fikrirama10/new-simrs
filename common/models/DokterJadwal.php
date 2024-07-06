<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dokter_jadwal".
 *
 * @property int $id
 * @property int|null $iddokter
 * @property int|null $idpoli
 * @property int|null $idhari
 * @property int|null $kuota
 * @property string|null $jam_mulai
 * @property string|null $jam_selesai
 * @property int|null $status
 *
 * @property Dokter $iddokter0
 * @property Hari $idhari0
 * @property Poli $idpoli0
 */
class DokterJadwal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dokter_jadwal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iddokter', 'idpoli', 'idhari', 'kuota', 'status'], 'integer'],
            [['jam_mulai', 'jam_selesai'], 'safe'],
            [['iddokter'], 'exist', 'skipOnError' => true, 'targetClass' => Dokter::className(), 'targetAttribute' => ['iddokter' => 'id']],
            [['idhari'], 'exist', 'skipOnError' => true, 'targetClass' => Hari::className(), 'targetAttribute' => ['idhari' => 'id']],
            [['idpoli'], 'exist', 'skipOnError' => true, 'targetClass' => Poli::className(), 'targetAttribute' => ['idpoli' => 'id']],
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
            'kuota' => 'Kuota',
            'jam_mulai' => 'Jam Mulai',
            'jam_selesai' => 'Jam Selesai',
            'status' => 'Masuk ?',
        ];
    }

    /**
     * Gets query for [[Iddokter0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }

    /**
     * Gets query for [[Idhari0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHari()
    {
        return $this->hasOne(Hari::className(), ['id' => 'idhari']);
    }

    /**
     * Gets query for [[Idpoli0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoli()
    {
        return $this->hasOne(Poli::className(), ['id' => 'idpoli']);
    }
}
