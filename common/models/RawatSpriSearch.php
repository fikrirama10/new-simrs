<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RawatSpri;

/**
 * RawatSpriSearch represents the model behind the search form of `common\models\RawatSpri`.
 */
class RawatSpriSearch extends RawatSpri
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idrawat', 'idjenisrawat', 'iddokter', 'idpoli', 'iduser', 'status', 'idbayar'], 'integer'],
            [['tgl_rawat', 'no_spri', 'no_rm'], 'safe'],
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
        $query = RawatSpri::find()->orderBy(['id'=>SORT_DESC]);

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
            'idrawat' => $this->idrawat,
            'idjenisrawat' => $this->idjenisrawat,
            'iddokter' => $this->iddokter,
            'idpoli' => $this->idpoli,
            'iduser' => $this->iduser,
            'tgl_rawat' => $this->tgl_rawat,
            'status' => $this->status,
            'idbayar' => $this->idbayar,
        ]);

        $query->andFilterWhere(['like', 'no_spri', $this->no_spri])
            ->andFilterWhere(['like', 'no_rm', $this->no_rm]);

        return $dataProvider;
    }
}
