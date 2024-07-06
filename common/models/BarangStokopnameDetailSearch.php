<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BarangStokopnameDetail;

/**
 * BarangStokopnameDetailSearch represents the model behind the search form of `common\models\BarangStokopnameDetail`.
 */
class BarangStokopnameDetailSearch extends BarangStokopnameDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idso', 'idbarang', 'stokasal', 'stokreal', 'selisih', 'harga', 'nilaiselisih', 'status'], 'integer'],
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
        $query = BarangStokopnameDetail::find()->joinWith(['barang as barang'])->orderBy(['barang.nama_barang'=>SORT_ASC]);

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
            'idso' => $this->idso,
            'idbarang' => $this->idbarang,
            'stokasal' => $this->stokasal,
            'stokreal' => $this->stokreal,
            'selisih' => $this->selisih,
            'harga' => $this->harga,
            'nilaiselisih' => $this->nilaiselisih,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
