<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Operasi;

/**
 * OperasiSearch represents the model behind the search form of `common\models\Operasi`.
 */
class OperasiSearch extends Operasi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idasal', 'idjenis', 'iddokter', 'idanastesi','status'], 'integer'],
            [['kode_ok', 'tgl_ok', 'laporan_pembedahan', 'diagnosisprabedah', 'icd10', 'icd9','no_rm'], 'safe'],
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
        $query = Operasi::find()->orderBy(['status'=>SORT_ASC,'kode_ok'=>SORT_DESC,]);

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
            'idasal' => $this->idasal,
            'tgl_ok' => $this->tgl_ok,
            'idjenis' => $this->idjenis,
            'iddokter' => $this->iddokter,
            'idanastesi' => $this->idanastesi,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'kode_ok', $this->kode_ok])
            ->andFilterWhere(['like', 'laporan_pembedahan', $this->laporan_pembedahan])
            ->andFilterWhere(['like', 'diagnosisprabedah', $this->diagnosisprabedah])
            ->andFilterWhere(['like', 'icd10', $this->icd10])
            ->andFilterWhere(['like', 'icd9', $this->icd9]);

        return $dataProvider;
    }
}
