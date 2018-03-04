<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput(['readonly'=>'readonly']);
echo $form->field($model,'new_password')->passwordInput();
echo $form->field($model,'password')->passwordInput();
echo "<button type='submit' class='btn btn-primary'>修改</button>";
\yii\bootstrap\ActiveForm::end();
