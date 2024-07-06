<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_diagnosajenis".
 *
 * @property int $id
 * @property string|null $jenis
 */
class SoapDiagnosajenis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_diagnosajenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis' => 'Jenis',
        ];
    }
}
