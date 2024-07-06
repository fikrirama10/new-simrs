<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaksi".
 *
 * @property int $id
 * @property string|null $idtransaksi
 * @property string|null $tgltransaksi
 * @property int|null $iduser
 * @property int|null $status
 * @property float|null $total
 * @property int|null $idkunjungan
 * @property int|null $no_rm
 * @property string|null $tgl_masuk
 * @property string|null $tgl_keluar
 */
class Transaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgltransaksi', 'tgl_masuk', 'tgl_keluar'], 'safe'],
            [['iduser', 'status', 'idkunjungan', 'no_rm','hide'], 'integer'],
            [['total','diskon','total_bayar','total_ditanggung'], 'number'],
            [['idtransaksi','kode_kunjungan'],'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idtransaksi' => 'Idtransaksi',
            'tgltransaksi' => 'Tgltransaksi',
            'iduser' => 'Iduser',
            'status' => 'Status',
            'total' => 'Total',
            'idkunjungan' => 'Idkunjungan',
            'no_rm' => 'No Rm',
            'tgl_masuk' => 'Tgl Masuk',
            'tgl_keluar' => 'Tgl Keluar',
        ];
    }
	public function genKode()
	{
		$tgl=date('dmY');
		$pf='TRX'.$tgl;	
	
		$max = $this::find()->select('max(idtransaksi)')->andFilterWhere(['like','idtransaksi',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->idtransaksi=$id;
		
	}
	public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }
}
