<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DataBarang;

/**
 * DataBarangSearch represents the model behind the search form of `common\models\DataBarang`.
 */
class DataBarangSearch extends DataBarang
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idsatuan', 'harga', 'status'], 'integer'],
            [['nama_barang', 'kode_barang', 'idkategori'], 'safe'],
            [['stok'], 'number'],
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
        $query = DataBarang::find()->orderBy(['nama_barang'=>SORT_ASC]);

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
            'idsatuan' => $this->idsatuan,
            'harga' => $this->harga,
            'stok' => $this->stok,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nama_barang', $this->nama_barang])
            ->andFilterWhere(['like', 'kode_barang', $this->kode_barang])
            ->andFilterWhere(['like', 'idkategori', $this->idkategori]);

        return $dataProvider;
    }
}
