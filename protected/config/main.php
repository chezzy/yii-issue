<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Issue-tracking Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	// application components
	'components'=>array(
		//CREATE USER 'ch4_issue'@'localhost' IDENTIFIED BY 'ch4_issue';
		//CREATE DATABASE IF NOT EXISTS  `ch4_issue` ;
		//GRANT ALL PRIVILEGES ON  `ch4\_issue` . * TO  'ch4_issue'@'localhost';
		'db' => array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=127.0.0.1;dbname=issue',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'schemaCachingDuration' => '3600',
			'enableProfiling' => true,
		),

		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>true,
			'rules'=>array(
				'/' => '/issue/index',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		)
	),

	'params' => array(
		'sendgrid' => require __DIR__ . '/params.php'
	)
);
