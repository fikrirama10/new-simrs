<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kategori_diagnosa".
 *
 * @property int $id
 * @property string|null $jenisdiagnosa
 * @property string|null $icd10
 */
class KategoriDiagnosa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kategori_diagnosa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenisdiagnosa', 'icd10'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenisdiagnosa' => 'Jenisdiagnosa',
            'icd10' => 'icd10',
        ];
    }
}
