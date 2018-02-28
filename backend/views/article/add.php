<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\ArticleCategory::getArticleCategory());
echo $form->field($model,'sort')->textInput();
echo $form->field($model2,'content')->widget('kucha\ueditor\UEditor',[]);
echo "<button type='submit' class='btn btn-primary'>".($model->getIsNewRecord()?'添加':'更新')."</button>";
\yii\bootstrap\ActiveForm::end();