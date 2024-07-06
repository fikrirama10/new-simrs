<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_droping_batch".
 *
 * @property int $id
 * @property int|null $minimal_stok
 * @property int|null $idobat
 * @property string|null $merk
 * @property string|null $no_batch
 * @property int|null $stok
 * @property string|null $ed
 * @property string|null $produksi
 */
class ObatDropingBatch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_droping_batch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['minimal_stok', 'idobat', 'stok'], 'integer'],
            [['ed', 'produksi'], 'safe'],
            [['merk', 'no_batch'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'minimal_stok' => 'Minimal Stok',
            'idobat' => 'Idobat',
            'merk' => 'Merk',
            'no_batch' => 'No Batch',
            'stok' => 'Stok',
            'ed' => 'Ed',
            'produksi' => 'Produksi',
        ];
    }
}
