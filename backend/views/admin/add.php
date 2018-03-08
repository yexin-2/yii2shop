<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $model->getIsNewRecord()?$form->field($model,'password')->textInput():'';
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList(['禁用','启用']);
echo $form->field($model,'role',['inline'=>1])->checkboxList(\backend\models\AddRole::getRoles());
echo "<button type='submit' class='btn btn-primary'>".($model->getIsNewRecord()?'添加':'更新')."</button>";
\yii\bootstrap\ActiveForm::end();