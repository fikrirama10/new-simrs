<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_stokopname".
 *
 * @property int $id
 * @property string $kode_so
 * @property string $tgl_so
 * @property int $idperiode
 * @property string|null $jam_mulai
 * @property string|null $jam_selesai
 * @property float|null $lama
 * @property float|null $selisih
 * @property string $keterangan
 * @property int|null $iduser
 * @property int|null $idgudang
 */
class ObatStokopname extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_stokopname';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl_so'], 'required'],
            [['tgl_so', 'jam_mulai', 'jam_selesai'], 'safe'],
            [['idperiode', 'iduser', 'idgudang','status'], 'integer'],
            [['lama', 'selisih'], 'number'],
            [['keterangan'], 'string'],
            [['kode_so'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_so' => 'Kode So',
            'tgl_so' => 'Tgl So',
            'idperiode' => 'Idperiode',
            'jam_mulai' => 'Jam Mulai',
            'jam_selesai' => 'Jam Selesai',
            'lama' => 'Lama',
            'selisih' => 'Selisih',
            'keterangan' => 'Keterangan',
            'iduser' => 'Iduser',
            'idgudang' => 'Idgudang',
        ];
    }
	public function genKode($periode)
	{
		$tgl=date('Ymd');
		$pf='SO'.$periode.$tgl;	
	
		$max = $this::find()->select('max(kode_so)')->andFilterWhere(['like','kode_so',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_so=$id;
		
	}
	public function getPeriode()
    {
        return $this->hasOne(ObatStokopnamePeriode::className(), ['id' => 'idperiode']);
    }
	public function getGudang()
    {
        return $this->hasOne(Gudang::className(), ['id' => 'idgudang']);
    }
}
