<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObatFarmasi;

/**
 * ObatFarmasiSearch represents the model behind the search form of `common\models\ObatFarmasi`.
 */
class ObatFarmasiSearch extends ObatFarmasi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjenis', 'total_harga', 'tuslah', 'obat_racik', 'jasa_racik', 'keuntungan', 'status','nama_pasien','nrp','alamat'], 'integer'],
            [['kode_resep', 'tgl'], 'safe'],
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
        $query = ObatFarmasi::find();

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
            'tgl' => $this->tgl,
            'idjenis' => $this->idjenis,
            'total_harga' => $this->total_harga,
            'tuslah' => $this->tuslah,
            'obat_racik' => $this->obat_racik,
            'jasa_racik' => $this->jasa_racik,
            'keuntungan' => $this->keuntungan,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'kode_resep', $this->kode_resep])
		->andFilterWhere(['like', 'nama_pasien', $this->nama_pasien])
		->andFilterWhere(['like', 'nrp', $this->nrp]);

        return $dataProvider;
    }
}
