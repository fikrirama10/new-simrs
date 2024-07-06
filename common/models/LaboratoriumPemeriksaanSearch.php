<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LaboratoriumPemeriksaan;

/**
 * LaboratoriumPemeriksaanSearch represents the model behind the search form of `common\models\LaboratoriumPemeriksaan`.
 */
class LaboratoriumPemeriksaanSearch extends LaboratoriumPemeriksaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idlab', 'idjenis', 'urutan', 'idtindakan'], 'integer'],
            [['nama_pemeriksaan', 'kode_lab'], 'safe'],
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
        $query = LaboratoriumPemeriksaan::find();

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
            'idlab' => $this->idlab,
            'idjenis' => $this->idjenis,
            'urutan' => $this->urutan,
            'idtindakan' => $this->idtindakan,
        ]);

        $query->andFilterWhere(['like', 'nama_pemeriksaan', $this->nama_pemeriksaan])
            ->andFilterWhere(['like', 'kode_lab', $this->kode_lab]);

        return $dataProvider;
    }
}
