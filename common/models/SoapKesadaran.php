<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_kesadaran".
 *
 * @property int $id
 * @property string|null $kesadaran
 * @property string|null $keterangan
 */
class SoapKesadaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_kesadaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kesadaran', 'keterangan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kesadaran' => 'Kesadaran',
            'keterangan' => 'Keterangan',
        ];
    }
}
