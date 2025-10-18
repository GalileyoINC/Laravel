@extends('layouts.app')

@section('title', 'Help - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-start mb-4">
        <a href="{{ route('help.log') }}" class="btn btn-primary">Logs</a>
        <a href="{{ route('help.check-sps') }}" class="btn btn-warning ml-2">Check SPS</a>
    </div>

    <hr>

    <ul class="list-unstyled">
        <li><a href="#" class="btn btn-link">Grape JS</a></li>
        <li><a href="#" class="btn btn-link">Grape JS 2</a></li>
        <li><a href="#" class="btn btn-link">Bootstrap Wysiwyg</a></li>
    </ul>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>Grid</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Field 1</th>
                                <th>Field 2</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <i class="fas fa-check text-success"></i>
                                </td>
                                <td>
                                    <i class="fas fa-times text-danger"></i>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info">View</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel-footer">
                    <p>Use <code>'field1:boolean'</code> to show <i class="fas fa-times"></i><i class="fas fa-check"></i></p>
                    <p>You can change this in <b>main.php</b> <code>[components][formatter][booleanFormat]</code></p>
                    <hr>

                    <p>use <code>['class' => 'backend\components\ActionColumn'],</code> for add buttons (default is 3)</p>
                    <pre>
[
    'class' => 'backend\components\ActionColumn',
    'template' => '&lt;div class="btn-group">{view}&lt;/div>',
],
[
    'class' => 'backend\components\ActionColumn',
    'template' => '&lt;div class="btn-group">{delete}{view}{update}{view}{delete}&lt;/div>',
],</pre>
                    </p>
                    <p>If you add own button and use font awesome add class <code>fa-fw</code> (<code><\i class="fas fa-cog fa-fw"></\i></code>)</p>
                    <p>If you want to open in modal you must add class <code>.JS__load_in_modal</code> to link</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>Controller</span>
                </div>
                <div class="panel-body">
                    <p>In controller you can use <code>$this->renderGrid('view', [])</code> or <code>$this->renderForm('view', [])</code> add content returns in modal if you get by ajax</p>
                    <a href="{{ route('help.test-modal') }}" class="JS__load_in_modal btn btn-info">You can open in modal</a>
                    <a href="{{ route('help.test-modal') }}" class="btn btn-primary">Or as page</a>

                    <hr>
                    <p>Use <code>return $this->renderFlash('success', 'text')</code> to show alerts. The summoned will disappear</p>

                    <a href="{{ route('help.test-alert', ['type' => 'success']) }}" class="JS__test_alert btn btn-success">Show success alert</a>
                    <a href="{{ route('help.test-alert', ['type' => 'error']) }}" class="JS__test_alert btn btn-danger">Show error alert</a>
                    <a href="{{ route('help.test-alert', ['type' => 'info']) }}" class="JS__test_alert btn btn-info">Show info alert</a>
                    <a href="{{ route('help.test-alert', ['type' => 'warning']) }}" class="JS__test_alert btn btn-warning">Show warning alert</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>Gulp</span>
                </div>
                <div class="panel-body">
                    <p>You must change js & css files in dir <code>fish\backend\gulp_build</code></p>
                    <p>Commands for this directory</p>
                    <ul>
                        <li><code>gulp build</code> compile js, less and css files</li>
                        <li><code>gulp</code> init watches for js and less files in work directory</li>
                        <li><code>gulp rebuild</code> Clean assets, compile bootstrap, build</li>
                    </ul>
                    <p>for init gulp in first time run <code>npm install</code></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>Js</span>
                </div>
                <div class="panel-body">
                    <p><code>core.setSession(key, value)</code> set data to maintenance/set-session</p>
                    <p><code>core.showLoader()</code> <code>core.hideLoader()</code></p>
                    <p><code>core.showAlert({type: "error", value: "Test alert"})</code> to show alert</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>Mailer</span>
                </div>
                <div class="panel-body">
<pre>
$message = \Yii::$app->mailer->compose()
    ->setFrom('from@domain.com')
    ->setTo('test@example.com')
    ->setSubject('Subject')
    ->setTextBody('Body')
    ->setHtmlBody('<\b>Body<\/b>');
</pre>
                    <p>And then you can:</p>
                    <ul>
                        <li><code>$message->sendLater();</code> Only add message to EmailPool</li>
                        <li><code>$message->sendInBackground();</code> Add message to EmailPool AND call the console command <code>mail/send</code></li>
                        <li><code>$message->send();</code> Send by usual</li>
                    </ul>
                    <p><b class="text-danger">Important:</b> method <code>send()</code> didn`t add message to EmailPool. if you whant to add you can use <code>$message->saveToSpool();</code></p>

                    <br>
                    <p>Example</p>
<pre>
$message = \Yii::$app->mailer->composeByTemplate(\common\models\EmailTemplate::ID_PASSWORD_RESET_TOKEN, [
'first_name' => 'Drizzt',
'last_name' => 'Do\'Urden',
'homeland' => 'Menzoberranzan'
])
    ->setTo('test@example.com')
    ->sendInBackground();
</pre>
                    <a href="{{ route('help.test-mail') }}" class="JS__test_alert btn btn-success">Send Test Mail</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>SMS</span>
                </div>
                <div class="panel-body">
                    {{ route('help.sms', ['provider' => 'twilio', 'number' => '111111', 'body' => 'TEXT']) }}
                    <br>
                    {{ route('help.sms', ['provider' => 'sinch', 'number' => '111111', 'body' => 'TEXT']) }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>Formatter</span>
                </div>
                <div class="panel-body">
                    <p>
                        Wrong: <code>echo $userPlan->end_at->format('Y, M d H:i');</code>, not converted timezone <br>
                        Wrong: <code>echo $userPlan->end_at->format(Yii::$app->formatter->datetimeFormat);</code>, not converted timezone <br>
                        Right: <code>echo Yii::$app->formatter->asDatetime($userPlan->end_at);</code>
                    </p>

<pre>
<\?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'is_active',
        'created_at:DateTime', <b>//Right</b>
        'updated_at', <b>//Wrong</b>
    ],
]) ?>
</pre>

<pre>
[
    'attribute' => 'updated_at',
    'format' => ['DateTime'], <b>OR ['Date']</b>
    'filter' => DatePicker::widget([
        'model' => $searchModel,
        'attribute' => 'updated_at'
    ])
],
</pre>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>PHP Info</span>
                </div>
                <div class="panel-body">
                    <pre>{{ phpinfo() }}</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('a.JS__test_alert').click(function (e) {
        e.preventDefault()
        $.post($(this).attr('href'))
    })
})
</script>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}
.panel-body {
    padding: 15px;
}
.panel-footer {
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
    padding: 10px 15px;
}
.text-success {
    color: #28a745;
}
.text-danger {
    color: #dc3545;
}
pre {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.25rem;
    padding: 0.75rem;
    margin: 0.5rem 0;
}
code {
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    padding: 0.125rem 0.25rem;
    font-size: 0.875em;
}
</style>
@endsection
