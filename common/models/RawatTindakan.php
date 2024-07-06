<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_tindakan".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $tgl
 * @property int|null $idtindakan
 * @property int|null $idbayar
 * @property int|null $iddokter
 * @property string|null $tindakan
 * @property int|null $idkunjungan
 */
class RawatTindakan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_tindakan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idtindakan', 'idbayar', 'iddokter', 'idkunjungan'], 'integer'],
            [['tgl','jam'], 'safe'],
            [['tindakan'], 'string'],
            [['no_rm'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idrawat' => 'Idrawat',
            'no_rm' => 'No Rm',
            'tgl' => 'Tgl',
            'idtindakan' => 'Idtindakan',
            'idbayar' => 'Idbayar',
            'iddokter' => 'Iddokter',
            'tindakan' => 'Tindakan',
            'idkunjungan' => 'Idkunjungan',
        ];
    }
	public function getTindakans()
    {
        return $this->hasOne(Tarif::className(), ['id' => 'idtindakan']);
    }
	public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
    public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
    
}
