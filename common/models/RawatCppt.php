<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_cppt".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $profesi
 * @property string|null $tgl
 * @property string|null $jam
 * @property string|null $subjektif
 * @property string|null $objektif
 * @property string|null $plan
 * @property string|null $asesmen
 * @property int|null $idpetugas
 * @property int|null $iddokter
 * @property int|null $idruangan
 */
class RawatCppt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_cppt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idpetugas', 'iddokter', 'idruangan'], 'integer'],
            [['profesi', 'subjektif', 'objektif', 'plan', 'asesmen'], 'string'],
            [['tgl', 'jam'], 'safe'],
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
            'profesi' => 'Profesi',
            'tgl' => 'Tgl',
            'jam' => 'Jam',
            'subjektif' => 'Subjektif',
            'objektif' => 'Objektif',
            'plan' => 'Plan',
            'asesmen' => 'Asesmen',
            'idpetugas' => 'Idpetugas',
            'iddokter' => 'Iddokter',
            'idruangan' => 'Idruangan',
        ];
    }
		public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'idpetugas']);
    }
}
