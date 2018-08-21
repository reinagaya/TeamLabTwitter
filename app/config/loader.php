<?php

// ローダの初期化
$loader = new \Phalcon\Loader();

// コントローラとモデルに含まれるクラスを読み込む
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
)->register();