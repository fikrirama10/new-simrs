<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RawatResep;

/**
 * RawatResepSearch represents the model behind the search form of `common\models\RawatResep`.
 */
class RawatResepSearch extends RawatResep
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idrawat', 'no_rm', 'iddokter', 'status', 'idjenisrawat'], 'integer'],
            [['kode_resep', 'tgl_resep', 'jam_resep'], 'safe'],
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
        $query = RawatResep::find();

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
            'idrawat' => $this->idrawat,
            'no_rm' => $this->no_rm,
            'iddokter' => $this->iddokter,
            'tgl_resep' => $this->tgl_resep,
            'jam_resep' => $this->jam_resep,
            'status' => $this->status,
            'idjenisrawat' => $this->idjenisrawat,
        ]);

        $query->andFilterWhere(['like', 'kode_resep', $this->kode_resep]);

        return $dataProvider;
    }
}
