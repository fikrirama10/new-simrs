<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\checkbox\CheckboxX;
use yii\widgets\ActiveForm;
use kartik\file\FileInput
?>


	<div class='row'>
		<div class='col-md-4'>
		<?php $form = ActiveForm::begin(); ?>
			<?= $form->field($klpcm_dokumen, 'dokumen')->widget(FileInput::classname(), [
				'options' => ['accept' => 'image/*'],
				'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png','pdf','xps','doc','docx','xls','xlsx','ppt','pptx','rar','zip','jpeg','mp3','wav','txt']]]);?>
				<?= $form->field($klpcm_dokumen, 'idklpcm')->hiddenInput(['value'=>$model->id])->label(false) ?>	
				
		<?php ActiveForm::end(); ?>
		</div>
		<div class='col-md-6'>
		<h4>Dokumen</h4>
		<table class='table table-bordered'>
			<tr>
				<th>#</th>
				<th width=10>No</th>
				<th>Dokumen</th>
				<th>Keterangan</th>
			</tr>
			<?php $no=1; foreach($dokumen as $d){ ?>
			<tr>
				<td width=10><a href='<?= Url::to(['klpcm/hapus-dokumen?id='.$d->id])?>' class='btn btn-danger btn-xs'><span class='fa fa-trash'></span></a></td>
				<td><?= $no++ ?></td>
				<td>
					<a href='#' data-toggle="modal" data-target="#exampleModalLong-<?= $d->id ?>"><?= $d->dokumen ?></a></td>
						<div id="exampleModalLong-<?= $d->id?>" class="modal fade bd-example-modal-lg<?= $d->id ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-body">
										<div class='row'>
											<div class="modal-body">

												<div class="PDF">
													<object data="<?= 'https://new-simrs.rsausulaiman.com/frontend/dokumen/'.$d->dokumen ?>" type="application/pdf" width="750" height="750">
													alt : <a href="<?= 'https://new-simrs.rsausulaiman.com/frontend/dokumen/'.$d->dokumen ?>"><?= $d->dokumen?></a>
													</object>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>			
									</div>
								</div>
							<!-- /.modal-content -->
							</div>
						<!-- /.modal-dialog -->
						</div>
				</td>
				<td><?= $d->keterangan ?></td>
			</tr>
			<?php } ?>
		</table>
		</div>
	</div>
