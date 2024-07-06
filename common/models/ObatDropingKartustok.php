<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_droping_kartustok".
 *
 * @property int $id
 * @property int|null $idobat
 * @property int|null $idbacth
 * @property int|null $jumlah
 * @property int|null $jenis
 * @property string|null $tgl
 */
class ObatDropingKartustok extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_droping_kartustok';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idobat', 'idbacth', 'jumlah', 'jenis'], 'integer'],
            [['tgl'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idobat' => 'Idobat',
            'idbacth' => 'Idbacth',
            'jumlah' => 'Jumlah',
            'jenis' => 'Jenis',
            'tgl' => 'Tgl',
        ];
    }
}
