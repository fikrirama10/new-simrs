<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObatBacth;

/**
 * ObatBacthSearch represents the model behind the search form of `common\models\ObatBacth`.
 */
class ObatBacthSearch extends ObatBacth
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idobat', 'idsuplier', 'idbayar', 'idsatuan'], 'integer'],
            [['no_bacth', 'merk', 'tgl_produksi', 'tgl_kadaluarsa'], 'safe'],
            [['stok_apotek', 'stok_gudang', 'harga_jual', 'harga_beli'], 'number'],
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
        $query = ObatBacth::find()->joinWith(['obat as obat'])->orderBy(['obat.nama_obat'=>SORT_ASC]);;

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
            'idobat' => $this->idobat,
            'idsuplier' => $this->idsuplier,
            'stok_apotek' => $this->stok_apotek,
            'stok_gudang' => $this->stok_gudang,
            'idbayar' => $this->idbayar,
            'tgl_produksi' => $this->tgl_produksi,
            'tgl_kadaluarsa' => $this->tgl_kadaluarsa,
            'idsatuan' => $this->idsatuan,
            'harga_jual' => $this->harga_jual,
            'harga_beli' => $this->harga_beli,
        ]);

        $query->andFilterWhere(['like', 'no_bacth', $this->no_bacth])
            ->andFilterWhere(['like', 'merk', $this->merk]);

        return $dataProvider;
    }
}
