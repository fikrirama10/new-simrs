<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PenerimaanBarang;

/**
 * PenerimaanBarangSearch represents the model behind the search form of `common\models\PenerimaanBarang`.
 */
class PenerimaanBarangSearch extends PenerimaanBarang
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'idsuplier', 'iduser'], 'integer'],
            [['kode_penerimaan', 'no_faktur', 'no_up', 'tgl_faktur'], 'safe'],
            [['nilai_faktur', 'nilai_bayar', 'nilai_sisa'], 'number'],
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
        $query = PenerimaanBarang::find();

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
            'nilai_faktur' => $this->nilai_faktur,
            'nilai_bayar' => $this->nilai_bayar,
            'nilai_sisa' => $this->nilai_sisa,
            'status' => $this->status,
            'tgl_faktur' => $this->tgl_faktur,
            'idsuplier' => $this->idsuplier,
            'iduser' => $this->iduser,
        ]);

        $query->andFilterWhere(['like', 'kode_penerimaan', $this->kode_penerimaan])
            ->andFilterWhere(['like', 'no_faktur', $this->no_faktur])
            ->andFilterWhere(['like', 'no_up', $this->no_up]);

        return $dataProvider;
    }
}
