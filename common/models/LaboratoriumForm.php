<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "laboratorium_form".
 *
 * @property int $id
 * @property string|null $form
 * @property int|null $idpelayanan
 * @property int|null $idpemeriksaan
 * @property string|null $satuan
 * @property string|null $nilai_normallaki
 * @property string|null $nilai_normalp
 * @property int|null $urutan
 * @property int|null $status
 *
 * @property LaboratoriumLayanan $idpelayanan0
 * @property LaboratoriumPemeriksaan $idpemeriksaan0
 */
class LaboratoriumForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'laboratorium_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpelayanan', 'idpemeriksaan', 'urutan', 'status'], 'integer'],
            [['form'], 'string', 'max' => 150],
            [['satuan', 'nilai_normallaki', 'nilai_normalp'], 'string', 'max' => 50],
            [['idpelayanan'], 'exist', 'skipOnError' => true, 'targetClass' => LaboratoriumLayanan::className(), 'targetAttribute' => ['idpelayanan' => 'id']],
            [['idpemeriksaan'], 'exist', 'skipOnError' => true, 'targetClass' => LaboratoriumPemeriksaan::className(), 'targetAttribute' => ['idpemeriksaan' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form' => 'Form',
            'idpelayanan' => 'Idpelayanan',
            'idpemeriksaan' => 'Idpemeriksaan',
            'satuan' => 'Satuan',
            'nilai_normallaki' => 'Nilai Normallaki',
            'nilai_normalp' => 'Nilai Normalp',
            'urutan' => 'Urutan',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Idpelayanan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpelayanan0()
    {
        return $this->hasOne(LaboratoriumLayanan::className(), ['id' => 'idpelayanan']);
    }

    /**
     * Gets query for [[Idpemeriksaan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpemeriksaan0()
    {
        return $this->hasOne(LaboratoriumPemeriksaan::className(), ['id' => 'idpemeriksaan']);
    }
}
