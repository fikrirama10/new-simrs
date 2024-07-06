<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_stokopname_periode".
 *
 * @property int $id
 * @property string|null $periode
 */
class ObatStokopnamePeriode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_stokopname_periode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periode'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'periode' => 'Periode',
        ];
    }
}
