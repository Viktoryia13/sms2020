<?php

namespace app\modules\admin1\controllers;

use yii\web\Controller;

/**
 * Default controller for the `admin1` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
