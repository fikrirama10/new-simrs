<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_implementasi".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $tgl
 * @property string|null $jam
 * @property string|null $implementasi
 * @property int|null $idpetugas
 */
class RawatImplementasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_implementasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idpetugas','idruangan'], 'integer'],
            [['tgl', 'jam'], 'safe'],
            [['implementasi'], 'string'],
            [['no_rm'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idrawat' => 'Idrawat',
            'no_rm' => 'No Rm',
            'tgl' => 'Tgl',
            'jam' => 'Jam',
            'implementasi' => 'Implementasi',
            'idpetugas' => 'Idpetugas',
        ];
    }
	public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'idpetugas']);
    }
}
