<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ruangan_kelas".
 *
 * @property int $id
 * @property string|null $kelas
 * @property int|null $ket
 *
 * @property Ruangan[] $ruangans
 */
class RuanganKelas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ruangan_kelas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ket'], 'integer'],
            [['kelas'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kelas' => 'Kelas',
            'ket' => 'Ket',
        ];
    }

    /**
     * Gets query for [[Ruangans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuangans()
    {
        return $this->hasMany(Ruangan::className(), ['idkelas' => 'id']);
    }
}
