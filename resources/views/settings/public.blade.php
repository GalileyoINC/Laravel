@extends('layouts.app')

@section('title', 'Public Settings - Admin')

@section('content')
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Public Settings</h3>
        </div>

        <div class="panel-body">
            <!-- Legacy tabs -->
            <ul class="nav nav-tabs" role="tablist" id="JS__settings_tab">
                <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Main</a></li>
                <li role="presentation"><a href="#attention" aria-controls="attention" role="tab" data-toggle="tab">Attention</a></li>
                <li role="presentation"><a href="#user_point" aria-controls="user_point" role="tab" data-toggle="tab">User Points</a></li>
            </ul>

            <div class="tab-content" style="margin-top:15px;">
                <!-- MAIN TAB -->
                <div role="tabpanel" class="tab-pane active" id="main">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <fieldset class="mt-10">
                                <legend>Public Configuration</legend>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            @foreach(($model->all() ?? []) as $key => $value)
                                                @continue(in_array($key, ['_token']))
                                                <tr>
                                                    <th>{{ $key }}</th>
                                                    <td>
                                                        @if(is_array($value))
                                                            <pre style="margin:0;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            {{ (string) $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-lg-6 col-md-6 bg-admin">
                            <fieldset class="mt-10">
                                <legend>User Point Overview</legend>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>Enabled</th>
                                                <td>{{ $userPointSettingsForm->input('user_point__enabled') ? 'Yes' : 'No' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Points per Dollar</th>
                                                <td>{{ $userPointSettingsForm->input('user_point__points_per_dollar') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Points per Referral</th>
                                                <td>{{ $userPointSettingsForm->input('user_point__points_per_referral') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Points per Login</th>
                                                <td>{{ $userPointSettingsForm->input('user_point__points_per_login') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Points per Share</th>
                                                <td>{{ $userPointSettingsForm->input('user_point__points_per_share') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Max Points per Day</th>
                                                <td>{{ $userPointSettingsForm->input('user_point__max_points_per_day') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Redemption Rate</th>
                                                <td>{{ $userPointSettingsForm->input('user_point__redemption_rate') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>

                <!-- ATTENTION TAB -->
                <div role="tabpanel" class="tab-pane" id="attention">
                    <fieldset class="mt-10">
                        <legend>Attention</legend>
                        <div class="row">
                            <div class="col-lg-8 col-md-8">
                                <div class="form-group">
                                    <label>Attention Message Text</label>
                                    <textarea class="form-control" rows="6" readonly>{{ \App\Models\System\Settings::get('attention_message__text', '') }}</textarea>
                                    <small class="text-muted">The message will be shown under the main menu</small>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label>Attention Message Mode</label>
                                    @php($mode = (int) \App\Models\System\Settings::get('attention_message__mode', 0))
                                    <input type="text" class="form-control" readonly value="{{ [0=>'Disabled',1=>'Guest',2=>'Auth',3=>'All'][$mode] ?? 'Disabled' }}">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!-- USER POINTS TAB -->
                <div role="tabpanel" class="tab-pane" id="user_point">
                    <fieldset class="mt-10">
                        <legend>User Points</legend>
                        <div class="row">
                            <div class="col-lg-3 col-md-4">
                                <div class="form-group"><label>Comments</label><input type="text" class="form-control" readonly value="{{ $userPointSettingsForm->input('comment_point') ?? 0 }}"></div>
                                <div class="form-group"><label>Public Feeds</label><input type="text" class="form-control" readonly value="{{ $userPointSettingsForm->input('public_feed_point') ?? 0 }}"></div>
                                <div class="form-group"><label>Reactions</label><input type="text" class="form-control" readonly value="{{ $userPointSettingsForm->input('reaction_point') ?? 0 }}"></div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
