<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
		'algo' => [
			'class' => 'common\components\AlgoFunction',
		],
		'kazo' => [
			'class' => 'common\components\FikriFunction',
		],
		'vclaim' => [
			'class' => 'common\components\VclaimFunction',
		],
		'hfis' => [
			'class' => 'common\components\HfisFunction',
		],
		'sep' => [
			'class' => 'common\components\SepFunction',
		],
		'rujukan' => [
			'class' => 'common\components\RujukanFunction',
		],
		'kontrol' => [
			'class' => 'common\components\KontrolFunction',
		],
		'monitoring' => [
			'class' => 'common\components\MonitoringFunction',
		],
		'prb' => [
			'class' => 'common\components\PrbFunction',
		],
		'bpjs' => [
			'class' => 'common\components\BridgingFunction',
		],
		'jwt' => [
			'class' => \sizeg\jwt\Jwt::class,
			'key' => 'secret',
			// You have to configure ValidationData informing all claims you want to validate the token.
			//'jwtValidationData' => \api\components\JwtValidationData::class
		],
    ],
	'modules' => [
		'datecontrol' =>  [
			'class' => '\kartik\datecontrol\Module'
			],
		'gridview' =>  [
			'class' => '\kartik\grid\Module'
			],
	],
];
