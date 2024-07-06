<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_ruangan".
 *
 * @property int $id
 * @property int|null $idkunjungan
 * @property int|null $idrawat
 * @property float|null $los
 * @property string|null $no_rm
 * @property int|null $idruangan
 * @property int|null $idbayar
 * @property string|null $tgl_masuk
 * @property string|null $tgl_keluar
 * @property int|null $status
 * @property string|null $asal
 */
class RawatRuangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_ruangan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idkunjungan', 'idrawat', 'idruangan', 'idbayar', 'status','idbed','idkelas'], 'integer'],
            [['los'], 'number'],
            [['tgl_masuk', 'tgl_keluar'], 'safe'],
            [['no_rm', 'asal'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idkunjungan' => 'Idkunjungan',
            'idrawat' => 'Idrawat',
            'los' => 'Los',
            'no_rm' => 'No Rm',
            'idruangan' => 'Idruangan',
            'idbayar' => 'Idbayar',
            'tgl_masuk' => 'Tgl Masuk',
            'tgl_keluar' => 'Tgl Keluar',
            'status' => 'Status',
            'asal' => 'Asal',
        ];
    }
	
	public function getRuangan()
    {
        return $this->hasOne(Ruangan::className(), ['id' => 'idruangan']);
    }
	public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
}
