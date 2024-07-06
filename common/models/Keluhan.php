<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "keluhan".
 *
 * @property int $id
 * @property string $kode_p
 * @property string $no_rekmed
 * @property string|null $keluhan
 * @property string|null $rwt_penyakits
 * @property string|null $rwt_penyakitd
 * @property string|null $rwt_penyakitk
 * @property string|null $alergi
 * @property string|null $rwt_pekerjaan
 * @property string|null $ketrwtp
 * @property string|null $tanggal
 * @property int|null $idpemeriksa
 * @property int|null $idtkp
 */
class Keluhan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'keluhan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_p', 'no_rekmed'], 'required'],
            [['keluhan', 'rwt_penyakits', 'rwt_penyakitd', 'rwt_penyakitk', 'rwt_pekerjaan', 'ketrwtp'], 'string'],
            [['tanggal'], 'safe'],
            [['idpemeriksa', 'idtkp'], 'integer'],
            [['kode_p', 'no_rekmed', 'alergi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_p' => 'Kode P',
            'no_rekmed' => 'No Rekmed',
            'keluhan' => 'Keluhan',
            'rwt_penyakits' => 'Rwt Penyakits',
            'rwt_penyakitd' => 'Rwt Penyakitd',
            'rwt_penyakitk' => 'Rwt Penyakitk',
            'alergi' => 'Alergi',
            'rwt_pekerjaan' => 'Rwt Pekerjaan',
            'ketrwtp' => 'Ketrwtp',
            'tanggal' => 'Tanggal',
            'idpemeriksa' => 'Idpemeriksa',
            'idtkp' => 'Idtkp',
        ];
    }
}
