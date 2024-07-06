<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BarangPenerimaan;

/**
 * BarangPenerimaanSearch represents the model behind the search form of `common\models\BarangPenerimaan`.
 */
class BarangPenerimaanSearch extends BarangPenerimaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idsuplier', 'status'], 'integer'],
            [['no_faktur', 'tgl'], 'safe'],
            [['total_penerimaan'], 'number'],
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
        $query = BarangPenerimaan::find();

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
            'tgl' => $this->tgl,
            'idsuplier' => $this->idsuplier,
            'status' => $this->status,
            'total_penerimaan' => $this->total_penerimaan,
        ]);

        $query->andFilterWhere(['like', 'no_faktur', $this->no_faktur]);

        return $dataProvider;
    }
}
