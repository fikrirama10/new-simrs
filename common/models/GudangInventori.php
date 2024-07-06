<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gudang_inventori".
 *
 * @property int $id
 * @property int|null $idgudang
 * @property int|null $idobat
 * @property float|null $stok
 * @property string|null $tgl_update
 */
class GudangInventori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gudang_inventori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idgudang', 'idobat'], 'integer'],
            [['stok'], 'number'],
            [['tgl_update'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idgudang' => 'Idgudang',
            'idobat' => 'Idobat',
            'stok' => 'Stok',
            'tgl_update' => 'Tgl Update',
        ];
    }
}
