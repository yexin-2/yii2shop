<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'codes')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'admin/captcha'
]);
echo $form->field($model,'auto_login')->checkboxList(['记住登录']);
echo "<button type='submit' class='btn btn-primary'>登录</button>";
\yii\bootstrap\ActiveForm::end();
