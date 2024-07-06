<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_kunjungan".
 *
 * @property int $id
 * @property string|null $idkunjungan
 * @property string|null $no_rm
 * @property string|null $tgl_kunjungan
 * @property string|null $jam_kunjungan
 * @property int|null $iduser
 * @property string|null $usia_kunjungan
 *
 * @property Pasien $noRm
 * @property User $iduser0
 */
class RawatKunjungan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_kunjungan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'iduser'], 'integer'],
            [['tgl_kunjungan', 'jam_kunjungan'], 'safe'],
            [['idkunjungan', 'no_rm', 'usia_kunjungan'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['no_rm'], 'exist', 'skipOnError' => true, 'targetClass' => Pasien::className(), 'targetAttribute' => ['no_rm' => 'no_rm']],
            [['iduser'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['iduser' => 'id']],
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
            'no_rm' => 'No Rm',
            'tgl_kunjungan' => 'Tgl Kunjungan',
            'jam_kunjungan' => 'Jam Kunjungan',
            'iduser' => 'Iduser',
            'usia_kunjungan' => 'Usia Kunjungan',
        ];
    }

    /**
     * Gets query for [[NoRm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }

    /**
     * Gets query for [[Iduser0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIduser0()
    {
        return $this->hasOne(User::className(), ['id' => 'iduser']);
    }
	public function genKode()
	{
		$tgl=date('dmY');
		$pf=$tgl;
		$max = $this::find()->select('max(idkunjungan)')->andFilterWhere(['like','idkunjungan',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->idkunjungan=$id;
	}
}
