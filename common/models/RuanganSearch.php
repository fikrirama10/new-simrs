<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ruangan;

/**
 * RuanganSearch represents the model behind the search form of `common\models\Ruangan`.
 */
class RuanganSearch extends Ruangan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjenis', 'idkelas', 'status', 'jenis'], 'integer'],
            [['nama_ruangan', 'kapasitas', 'gender', 'keterangan'], 'safe'],
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
        $query = Ruangan::find();

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
            'idjenis' => $this->idjenis,
            'idkelas' => $this->idkelas,
            'status' => $this->status,
            'jenis' => $this->jenis,
        ]);

        $query->andFilterWhere(['like', 'nama_ruangan', $this->nama_ruangan])
            ->andFilterWhere(['like', 'kapasitas', $this->kapasitas])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
