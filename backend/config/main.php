<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
	'name' => 'SIMRS',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
	'api' => [
            'class' => 'backend\modules\api\Api',
        ],
	],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
			'class' => 'common\components\Request',
			'web'=> '/backend/web',
			'adminUrl' => '/dashboard'
        ],
		'urlManager' => [
				'enablePrettyUrl' => true,
				'showScriptName' => false,
				
				'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<key:\w+>' => '<controller>/<action>',),
		],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'ppid-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		 'assetManager' => [
			'bundles' => [
				'dmstr\web\AdminLteAsset' => [
					'skin' => 'skin-black',
				],
				'wbraganca\dynamicform\DynamicFormAsset' => [
                    'sourcePath' => '@app/web/js',
                    'js' => [
                        'yii2-dynamic-form.js'
                    ],
                ],
				'kartik\form\ActiveFormAsset' => [
					'bsDependencyEnabled' => false 
				],
			],
			
        ],
        
    ],
    'params' => $params,
];
