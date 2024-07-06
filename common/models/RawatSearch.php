<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Rawat;

/**
 * RawatSearch represents the model behind the search form of `common\models\Rawat`.
 */
class RawatSearch extends Rawat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjenisrawat', 'idpoli', 'iddokter', 'idruangan', 'idkelas', 'idbayar', 'status', 'kunjungan'], 'integer'],
            [['idrawat', 'idkunjungan', 'no_rm', 'no_sep', 'no_rujukan', 'no_suratkontrol', 'tglmasuk', 'tglpulang', 'no_antrian', 'cara_datang', 'cara_keluar'], 'safe'],
            [['los'], 'number'],
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
    public function search($params, $where=null, $andWhere=null,$andWhere2=null,$andWhere3=null,$andWhereTgl=null, $andFilterWhere=null, $andFilterWhere2=null, $orderBy = null ,$groupBy=null)
    {
        $query = Rawat::find()->orderBy(['tglmasuk'=>SORT_DESC]);

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
		if($andWhere3){
            $query->andWhere($andWhere3);
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
            'idjenisrawat' => $this->idjenisrawat,
            'idpoli' => $this->idpoli,
            'iddokter' => $this->iddokter,
            'idruangan' => $this->idruangan,
            'idkelas' => $this->idkelas,
            'idbayar' => $this->idbayar,
            'tglpulang' => $this->tglpulang,
            'los' => $this->los,
            'status' => $this->status,
            'kunjungan' => $this->kunjungan,
        ]);

        $query->andFilterWhere(['like', 'idrawat', $this->idrawat])
            ->andFilterWhere(['like', 'idkunjungan', $this->idkunjungan])
            ->andFilterWhere(['like', 'no_rm', $this->no_rm])
            ->andFilterWhere(['like', 'no_sep', $this->no_sep])
            ->andFilterWhere(['like', 'tglmasuk', $this->tglmasuk])
            ->andFilterWhere(['like', 'no_rujukan', $this->no_rujukan])
            ->andFilterWhere(['like', 'no_suratkontrol', $this->no_suratkontrol])
            ->andFilterWhere(['like', 'no_antrian', $this->no_antrian])
            ->andFilterWhere(['like', 'cara_datang', $this->cara_datang])
            ->andFilterWhere(['like', 'cara_keluar', $this->cara_keluar]);

        return $dataProvider;
    }
}
