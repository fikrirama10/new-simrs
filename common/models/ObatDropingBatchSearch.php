<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObatDropingBatch;

/**
 * ObatDropingBatchSearch represents the model behind the search form of `common\models\ObatDropingBatch`.
 */
class ObatDropingBatchSearch extends ObatDropingBatch
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'minimal_stok', 'idobat', 'stok'], 'integer'],
            [['merk', 'no_batch', 'ed'], 'safe'],
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
        $query = ObatDropingBatch::find();

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
            'minimal_stok' => $this->minimal_stok,
            'idobat' => $this->idobat,
            'stok' => $this->stok,
            'ed' => $this->ed,
        ]);

        $query->andFilterWhere(['like', 'merk', $this->merk])
            ->andFilterWhere(['like', 'no_batch', $this->no_batch]);

        return $dataProvider;
    }
}
