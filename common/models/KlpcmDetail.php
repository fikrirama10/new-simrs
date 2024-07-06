<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "klpcm_detail".
 *
 * @property int $id
 * @property string|null $tgl
 * @property int|null $idklpcm
 * @property int|null $idformulir
 * @property int|null $idtidaklengkap
 * @property string|null $keterangan
 *
 * @property Klpcm $idklpcm0
 */
class KlpcmDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'klpcm_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl'], 'safe'],
            [['idklpcm', 'idtidaklengkap'], 'integer'],
            [['keterangan','idformulir'], 'string', 'max' => 50],
            [['idklpcm'], 'exist', 'skipOnError' => true, 'targetClass' => Klpcm::className(), 'targetAttribute' => ['idklpcm' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tgl' => 'Tgl',
            'idklpcm' => 'Idklpcm',
            'idformulir' => 'Idformulir',
            'idtidaklengkap' => 'Idtidaklengkap',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * Gets query for [[Idklpcm0]].
     *
     * @return \yii\db\ActiveQuery
     */
	public function Pecah($id){
	$data = json_decode($id);
		return implode('<br> - ',$data); 
			// for($i=0; $i < count($data); $i++){
				  // $array = $data[$i].',';
				  // echo $array;
				  
			// }
			//echo $data;
	}
    public function getIdklpcm0()
    {
        return $this->hasOne(Klpcm::className(), ['id' => 'idklpcm']);
    }
}
