<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "barang_penerimaan".
 *
 * @property int $id
 * @property string|null $no_faktur
 * @property string|null $tgl
 * @property int|null $idsuplier
 * @property int|null $status
 * @property float|null $total_penerimaan
 */
class BarangPenerimaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang_penerimaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl'], 'safe'],
            [['idsuplier', 'status'], 'integer'],
            [['total_penerimaan'], 'number'],
            [['no_faktur'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_faktur' => 'No Faktur',
            'tgl' => 'Tgl',
            'idsuplier' => 'Idsuplier',
            'status' => 'Status',
            'total_penerimaan' => 'Total Penerimaan',
        ];
    }
	public function getSuplier()
    {
        return $this->hasOne(ObatSuplier::className(), ['id' => 'idsuplier']);
    }
}
