<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_resep_detail".
 *
 * @property int $id
 * @property int|null $idresep
 * @property int|null $idobat
 * @property int|null $idbacth
 * @property string|null $dosis
 * @property int|null $qty
 * @property string|null $signa1
 * @property string|null $signa2
 * @property string|null $catatan
 */
class RawatResepDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_resep_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idresep', 'idobat', 'idbacth', 'qty','status'], 'integer'],
            [['catatan'], 'string'],
            [['dosis', 'signa1', 'signa2'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idresep' => 'Idresep',
            'idobat' => 'Idobat',
            'idbacth' => 'Idbacth',
            'dosis' => 'Dosis',
            'qty' => 'Qty',
            'signa1' => 'Signa1',
            'signa2' => 'Signa2',
            'catatan' => 'Catatan',
        ];
    }
	public function getMerk()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbacth']);
    }
}
