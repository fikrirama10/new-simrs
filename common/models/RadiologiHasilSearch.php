<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RadiologiHasil;

/**
 * RadiologiHasilSearch represents the model behind the search form of `common\models\RadiologiHasil`.
 */
class RadiologiHasilSearch extends RadiologiHasil
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idpetugas', 'iddokter', 'status', 'idbayar', 'idrawat'], 'integer'],
            [['idhasil', 'catatan', 'tgl_hasil', 'no_rm'], 'safe'],
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
        $query = RadiologiHasil::find()->orderBy(['tgl_hasil'=>SORT_DESC]);

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
            'idpetugas' => $this->idpetugas,
            'iddokter' => $this->iddokter,
            'tgl_hasil' => $this->tgl_hasil,
            'status' => $this->status,
            'idbayar' => $this->idbayar,
            'idrawat' => $this->idrawat,
        ]);

        $query->andFilterWhere(['like', 'idhasil', $this->idhasil])
            ->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'no_rm', $this->no_rm]);

        return $dataProvider;
    }
}
