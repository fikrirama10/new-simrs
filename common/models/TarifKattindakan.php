<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tarif_kattindakan".
 *
 * @property int $id
 * @property string|null $tindakan
 */
class TarifKattindakan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarif_kattindakan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tindakan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tindakan' => 'Tindakan',
        ];
    }
}
