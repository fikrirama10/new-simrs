<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kategori_penyakit_mulut".
 *
 * @property int $id
 * @property string|null $penyakit
 */
class KategoriPenyakitMulut extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kategori_penyakit_mulut';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['penyakit'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'penyakit' => 'Penyakit',
        ];
    }
}
