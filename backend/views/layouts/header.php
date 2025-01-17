<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\SettingSimrs;
$simrs = SettingSimrs::findOne(3);
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">s<span class="text-red"><strong>RS</strong></span></span><span class="logo-lg">sim<span class="text-red"><strong>RS</strong></span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"></span>
						
                    </a>
                    <ul class="dropdown-menu">
						<?= Yii::$app->user->identity->userdetail->nama ?>
                    </ul>
                </li>
                
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php if($simrs){ ?>
							<?= Html::img(Yii::$app->params['baseUrl2'].'/frontend/images/setting/'.$simrs->logo_rs, ['alt'=>'no picture', 'class'=>'user-image'])?>
						<?php }else{ ?>
							<?= Html::img(Yii::$app->params['baseUrl2'].'/frontend/images/LOGO_RUMKIT_SULAIMAN__2_-removebg-preview.png', ['alt'=>'no picture', 'class'=>'user-img','width'=>'20px'])?>
						<?php } ?>
                      
                        <span class="hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
						<?php if($simrs){ ?>
							<?= Html::img(Yii::$app->params['baseUrl2'].'/frontend/images/setting/'.$simrs->logo_rs, ['alt'=>'no picture', 'class'=>''])?>
						<?php }else{ ?>
							 <?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO_RUMKIT_SULAIMAN__2_-removebg-preview.png', ['alt'=>'no picture', 'class'=>''])?>
						<?php } ?>
                           

                            <p>
                               <?= Yii::$app->user->identity->userdetail->nama ?> - 
								<?php if($simrs){ ?>
                                <small><?= $simrs->nama_rs?></small>
								<?php } ?>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
