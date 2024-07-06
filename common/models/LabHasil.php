<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lab_hasil".
 *
 * @property int $id
 * @property int|null $iditem
 * @property string|null $item
 * @property int|null $idpemeriksaan
 * @property int|null $idhasil
 * @property int|null $idlayanan
 * @property string|null $hasil
 * @property string|null $nilai_rujukan
 * @property string|null $satuan
 * @property int|null $status
 */
class LabHasil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lab_hasil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iditem', 'idpemeriksaan', 'idhasil', 'idlayanan', 'status'], 'integer'],
            [['item', 'hasil', 'nilai_rujukan', 'satuan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iditem' => 'Iditem',
            'item' => 'Item',
            'idpemeriksaan' => 'Idpemeriksaan',
            'idhasil' => 'Idhasil',
            'idlayanan' => 'Idlayanan',
            'hasil' => 'Hasil',
            'nilai_rujukan' => 'Nilai Rujukan',
            'satuan' => 'Satuan',
            'status' => 'Status',
        ];
    }
		public function getLabitem()
    {
        return $this->hasOne(LaboratoriumForm::className(), ['id' => 'iditem']);
    }
}
