<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RawatKunjungan;

/**
 * RawatKunjunganSearch represents the model behind the search form of `common\models\RawatKunjungan`.
 */
class RawatKunjunganSearch extends RawatKunjungan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'iduser'], 'integer'],
            [['idkunjungan', 'no_rm', 'tgl_kunjungan', 'jam_kunjungan', 'usia_kunjungan'], 'safe'],
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
        $query = RawatKunjungan::find();

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
            'tgl_kunjungan' => $this->tgl_kunjungan,
            'jam_kunjungan' => $this->jam_kunjungan,
            'iduser' => $this->iduser,
        ]);

        $query->andFilterWhere(['like', 'idkunjungan', $this->idkunjungan])
            ->andFilterWhere(['like', 'no_rm', $this->no_rm])
            ->andFilterWhere(['like', 'usia_kunjungan', $this->usia_kunjungan]);

        return $dataProvider;
    }
}
