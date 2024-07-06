<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaksi_bayar".
 *
 * @property int $id
 * @property string|null $bayar
 * @property int|null $status
 */
class TransaksiBayar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaksi_bayar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['bayar'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bayar' => 'Bayar',
            'status' => 'Status',
        ];
    }
}
