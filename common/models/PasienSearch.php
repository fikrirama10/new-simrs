<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Pasien;

/**
 * PasienSearch represents the model behind the search form of `common\models\Pasien`.
 */
class PasienSearch extends Pasien
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'usia_tahun', 'usia_bulan', 'usia_hari', 'idpekerjaan', 'idagama', 'idgolongan_darah', 'idpendidikan', 'idhubungan'], 'integer'],
            [['no_rm', 'nik', 'no_bpjs', 'nama_pasien', 'tgllahir', 'tempat_lahir', 'kepesertaan_bpjs'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Pasien::find()->orderBy(['no_rm'=>SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tgllahir' => $this->tgllahir,
            'usia_tahun' => $this->usia_tahun,
            'usia_bulan' => $this->usia_bulan,
            'usia_hari' => $this->usia_hari,
            'idpekerjaan' => $this->idpekerjaan,
            'idagama' => $this->idagama,
            'idgolongan_darah' => $this->idgolongan_darah,
            'idpendidikan' => $this->idpendidikan,
            'idhubungan' => $this->idhubungan,
        ]);

        $query->andFilterWhere(['like', 'no_rm', $this->no_rm])
            ->andFilterWhere(['like', 'nik', $this->nik])
            ->andFilterWhere(['like', 'no_bpjs', $this->no_bpjs])
            ->andFilterWhere(['like', 'nama_pasien', $this->nama_pasien])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'kepesertaan_bpjs', $this->kepesertaan_bpjs]);

        return $dataProvider;
    }
}
