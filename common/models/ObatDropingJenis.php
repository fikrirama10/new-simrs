<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_droping_jenis".
 *
 * @property int $id
 * @property string|null $jenis
 */
class ObatDropingJenis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_droping_jenis';
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
