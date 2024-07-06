<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObatStokopnameDetail;

/**
 * ObatStokopnameDetailSearch represents the model behind the search form of `common\models\ObatStokopnameDetail`.
 */
class ObatStokopnameDetailSearch extends ObatStokopnameDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_so', 'idbarang', 'idbatch', 'stok_asal', 'jumlah', 'selisih', 'status'], 'integer'],
            [['harga', 'total'], 'number'],
            [['keterangan', 'merk', 'klarifikasi'], 'safe'],
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
        $query = ObatStokopnameDetail::find()->joinWith(['obat as obat'])->orderBy(['obat.nama_obat'=>SORT_ASC]);

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
            'id_so' => $this->id_so,
            'idbarang' => $this->idbarang,
            'idbatch' => $this->idbatch,
            'stok_asal' => $this->stok_asal,
            'jumlah' => $this->jumlah,
            'selisih' => $this->selisih,
            'harga' => $this->harga,
            'total' => $this->total,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'merk', $this->merk])
            ->andFilterWhere(['like', 'klarifikasi', $this->klarifikasi]);

        return $dataProvider;
    }
}
