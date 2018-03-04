<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'password_hash')->textInput();
echo "<button type='submit' class='btn btn-primary'>重置</button>";
\yii\bootstrap\ActiveForm::end();
