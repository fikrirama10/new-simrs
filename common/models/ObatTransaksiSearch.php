<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObatTransaksi;

/**
 * ObatTransaksiSearch represents the model behind the search form of `common\models\ObatTransaksi`.
 */
class ObatTransaksiSearch extends ObatTransaksi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjenis', 'idjenisrawat', 'idrawat', 'iduser'], 'integer'],
            [['idtrx', 'tgl', 'no_rm', 'jam'], 'safe'],
            [['total_harga', 'total_bayar', 'total_sisa'], 'number'],
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
    public function search($params, $where=null, $andWhere=null,$andWhere2=null,$andWhereTgl=null, $andFilterWhere=null, $andFilterWhere2=null, $orderBy = null ,$groupBy=null)
    {
        $query = ObatTransaksi::find()->orderBy(['tgl'=>SORT_DESC]);

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
		if($where){
			$query->where($where);
		}
		
		if($andWhere){
			$query->andWhere($andWhere);
		}
        if($andWhere2){
            $query->andWhere($andWhere2);
        }
		if($andWhereTgl){
            $query->andWhere($andWhereTgl);
        }
		
		if($andFilterWhere){
			$query->andFilterWhere($andFilterWhere);
		}
		
		if($andFilterWhere2){
			$query->andFilterWhere($andFilterWhere2);
		}
		
		if($orderBy){
			$query->orderBy($orderBy);
		}
		
		if($groupBy){
			$query->groupBy($groupBy);
		}


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'idjenis' => $this->idjenis,
            'tgl' => $this->tgl,
            'idjenisrawat' => $this->idjenisrawat,
            'total_harga' => $this->total_harga,
            'total_bayar' => $this->total_bayar,
            'total_sisa' => $this->total_sisa,
            'idrawat' => $this->idrawat,
            'jam' => $this->jam,
            'iduser' => $this->iduser,
        ]);

        $query->andFilterWhere(['like', 'idtrx', $this->idtrx])
            ->andFilterWhere(['like', 'no_rm', $this->no_rm]);

        return $dataProvider;
    }
}
