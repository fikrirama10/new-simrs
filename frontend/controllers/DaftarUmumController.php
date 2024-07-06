<?php

namespace frontend\controllers;

use Yii;
use common\models\DaftarUmum;
use common\models\DaftarUmumSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DaftarUmumController implements the CRUD actions for DaftarUmum model.
 */
class DaftarUmumController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DaftarUmum models.
     * @return mixed
     */
	public function get_content($url, $post = '') {
		
		// $data = "29250";
		// $secretKey = "5lQ5E30F4C";
		$data = "29855";
		$secretKey = "3rU307868B";
         // Computes the timestamp
        date_default_timezone_set('UTC');
        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        // Computes the signature by hashing the salt with the secret key as the key
		$signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);
		$encodedSignature = base64_encode($signature);
		//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$usecookie = __DIR__ . "/cookie.txt";
		$header[] = 'Content-Type: application/json;charset=utf-8';
		$header[] = "Accept-Encoding: gzip, deflate";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Accept-Language:  en-US,en;q=0.8,id;q=0.6";
		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		// curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");

		if ($post)
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$rs = curl_exec($ch);
		
		if(empty($rs)){
			//var_dump($rs, curl_error($ch));
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		return $rs;
	}
	
	public function actionSelesai($id){
		$noregistrasi =  base64_decode($id);
		$daftar = DaftarUmum::find()->where(['kodedaftar'=>$noregistrasi])->one();
		$response2=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cari-dokter?id='.$daftar->iddokter);
		$data_json2=json_decode($response2, true);
		return $this->render('selesai',[
			'data_json2'=>$data_json2,
			'daftar'=>$daftar,
		]);
	}
	public function actionBerhasil($id){
		$noregistrasi =  base64_decode($id);
		$daftar = DaftarUmum::find()->where(['kodedaftar'=>$noregistrasi])->one();
		$response2=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cari-dokter?id='.$daftar->iddokter);
		$data_json2=json_decode($response2, true);
		
		return $this->render('berhasil',[
			'data_json2'=>$data_json2,
			'model'=>$daftar,
		]);
	}
	public function actionKonfirmasi($id){
		$noregistrasi =  base64_decode($id);
		return $this->redirect(['/daftar-umum/selesai?id='.$id]);
	}
	
    public function actionIndex()
    {
        $model = new DaftarUmum();
		$response2 = $this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/poli');
		$data_poli = json_decode($response2, true);
		if($model->load(Yii::$app->request->post())){
			$model->genKode();
			$model->status = 1;
			if($model->save()){
				return $this->redirect(['/daftar-umum/berhasil?id='.base64_encode($model->kodedaftar)]);
			}
		}
        return $this->render('index', [
            'model' => $model,
            'data_poli' => $data_poli,
        ]);
    }
	public function actionShow($id){
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/dokter?id='.$id);
		$data_json=json_decode($response, true);
		$model = new DaftarUmum();
		return $this->renderAjax('show-dokter',[
			'data_json'=>$data_json,
			'model'=>$model,
		]);
	}
	public function actionCekDokter($id,$tgl){
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cek-jadwal?dokter='.$id.'&tgl='.$tgl);
		$data_json=json_decode($response, true);
		$response2=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cari-dokter?id='.$id);
		$data_json2=json_decode($response2, true);
		$model = new DaftarUmum();
		return $this->renderAjax('cek-dokter',[
			'data_json'=>$data_json,
			'data_json2'=>$data_json2,
			'tgl'=>$tgl,
			'id'=>$id,
			'model'=>$model,
		]);
	}
    /**
     * Displays a single DaftarUmum model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DaftarUmum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DaftarUmum();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DaftarUmum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DaftarUmum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DaftarUmum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DaftarUmum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DaftarUmum::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
