<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pasien_status".
 *
 * @property int $id
 * @property string|null $no_rm
 * @property int|null $idstatus
 * @property string|null $pangkat
 * @property string|null $nrp
 * @property string|null $kesatuan
 * @property string|null $keterangan
 *
 * @property DataStatus $idstatus0
 */
class PasienStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pasien_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idstatus','idpasien'], 'integer'],
            [['keterangan'], 'string'],
            [['no_rm', 'pangkat', 'nrp', 'kesatuan'], 'string', 'max' => 50],
            [['idstatus'], 'exist', 'skipOnError' => true, 'targetClass' => DataStatus::className(), 'targetAttribute' => ['idstatus' => 'id']],
            [['idpasien'], 'exist', 'skipOnError' => true, 'targetClass' => Pasien::className(), 'targetAttribute' => ['idpasien' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpasien' => 'idpasien',
            'no_rm' => 'No Rm',
            'idstatus' => 'Idstatus',
            'pangkat' => 'Pangkat',
            'nrp' => 'Nrp',
            'kesatuan' => 'Kesatuan',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * Gets query for [[Idstatus0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdstatus0()
    {
        return $this->hasOne(DataStatus::className(), ['id' => 'idstatus']);
    }
}
