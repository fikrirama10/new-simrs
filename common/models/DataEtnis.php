<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_etnis".
 *
 * @property int $id
 * @property string|null $etnis
 * @property string|null $keterangan
 */
class DataEtnis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_etnis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['etnis', 'keterangan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'etnis' => 'Etnis',
            'keterangan' => 'Keterangan',
        ];
    }
}
