<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class UserController extends Controller
{

    public $email;
    public $password;

    public function options()
    {
        return ['email', 'password'];
    }

    public function optionAliases()
    {
        return ['e' => 'email', 'p' => 'password'];
    }

    function actionCreateAdmin()
    {
        $userRole = Yii::$app->authManager->getRole('admin');
        if(!$userRole)
        {
            $role = Yii::$app->authManager->createRole('admin');
            $role->description = 'Админ';
            Yii::$app->authManager->add($role);

            $userRole = Yii::$app->authManager->getRole('admin');
        }

        while(empty($this->email))
        {
            echo "\n  Enter admin email: ";
            $this->email = trim(Console::input());
        }

        while(empty($this->password))
        {
            echo "\n  Enter admin password: ";
            $this->password = trim(Console::input());
        }


        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);

        if($user->save())
        {
            Yii::$app->authManager->assign($userRole, $user->id);

            $email = $this->ansiFormat($this->email, Console::FG_YELLOW);
            echo "\n  ..... Admin user with email: $email was created " . $this->ansiFormat('successful', Console::FG_GREEN) . ".....\n";
        }
        else
        {
            $this->stderr("\nYou have some errors\n", Console::FG_RED);
        }



    }
}