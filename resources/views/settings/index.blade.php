@extends('layouts.app')

@section('title', 'System Settings - Admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(!auth()->user()->showSettingsRO())
        <div class="d-flex justify-content-end mb-4">
            <form action="{{ route('settings.flush') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-sync-alt"></i> Flush
                </button>
            </form>
        </div>
    @endif

    <div class="box">
        <div class="box-body">
    <ul class="nav nav-tabs" role="tablist" id="JS__settings_tab">
        <li role="presentation" class="nav-item">
            <a class="nav-link active" href="#main" aria-controls="main" role="tab" data-bs-toggle="tab">Main</a>
        </li>
        <li role="presentation" class="nav-item">
            <a class="nav-link" href="#sms" aria-controls="sms" role="tab" data-bs-toggle="tab">SMS</a>
        </li>
        <li role="presentation" class="nav-item">
            <a class="nav-link" href="#app" aria-controls="app" role="tab" data-bs-toggle="tab">APP</a>
        </li>
            <li role="presentation" class="nav-item">
                <a class="nav-link" href="#api" aria-controls="api" role="tab" data-bs-toggle="tab">API</a>
            </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
            <form action="{{ route('settings.update-main') }}" method="POST">
                @csrf
                <fieldset class="mt-10">
                    <legend>System</legend>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-checkbox-shrink">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="system__show_signup" id="system__show_signup" value="1" {{ $mainForm->system__show_signup ? 'checked' : '' }}>
                                    <label class="form-check-label" for="system__show_signup">
                                        Show button "Sign up" on YES, and "Register" on NO
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6">
                            <div class="form-checkbox-shrink">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="active_record_log__on" id="active_record_log__on" value="1" {{ $mainForm->active_record_log__on ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active_record_log__on">
                                        Active Record Log
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="allowed_image_extensions">Allowed Image Extensions</label>
                                <input type="text" class="form-control" name="allowed_image_extensions" id="allowed_image_extensions" value="{{ $mainForm->allowed_image_extensions }}" maxlength="255">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="form-checkbox-shrink">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="archive_db_turn_on" id="archive_db_turn_on" value="1" {{ $mainForm->archive_db_turn_on ? 'checked' : '' }}>
                                    <label class="form-check-label" for="archive_db_turn_on">
                                        Archive DB Turn On
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mt-10">
                    <legend>Mail</legend>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="mail__sandbox_email">Sandbox Email</label>
                                <input type="email" class="form-control" name="mail__sandbox_email" id="mail__sandbox_email" value="{{ $mainForm->mail__sandbox_email }}" maxlength="255">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-checkbox">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mail__is_sandbox" id="mail__is_sandbox" value="1" {{ $mainForm->mail__is_sandbox ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mail__is_sandbox">
                                        Is Sandbox
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="mail__bcc_email">BCC Email</label>
                                <input type="email" class="form-control" name="mail__bcc_email" id="mail__bcc_email" value="{{ $mainForm->mail__bcc_email }}" maxlength="255">
                            </div>
                            <div class="form-group">
                                <label for="mail__tech_admin_email">Tech Admin Email</label>
                                <input type="email" class="form-control" name="mail__tech_admin_email" id="mail__tech_admin_email" value="{{ $mainForm->mail__tech_admin_email }}" maxlength="255">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-checkbox">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mail__is_bcc" id="mail__is_bcc" value="1" {{ $mainForm->mail__is_bcc ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mail__is_bcc">
                                        Is BCC
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="row">
                    <fieldset class="col-lg-4 col-md-6 mt-10">
                        <legend>IMAP</legend>

                        <div class="form-group">
                            <label for="mail_incoming__host">Host</label>
                            <input type="text" class="form-control" name="mail_incoming__host" id="mail_incoming__host" value="{{ $mainForm->mail_incoming__host }}" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="mail_incoming__login">Login</label>
                            <input type="text" class="form-control" name="mail_incoming__login" id="mail_incoming__login" value="{{ $mainForm->mail_incoming__login }}" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="mail_incoming__password">Password</label>
                            <input type="text" class="form-control" name="mail_incoming__password" id="mail_incoming__password" value="{{ $mainForm->mail_incoming__password }}" maxlength="255">
                        </div>
                    </fieldset>

                    <fieldset class="col-lg-4 col-md-6 mt-10">
                        <legend>Chat Socket</legend>

                        <div class="form-group">
                            <label for="chat_socket_url">Socket URL</label>
                            <input type="text" class="form-control" name="chat_socket_url" id="chat_socket_url" value="{{ $mainForm->chat_socket_url }}" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="chat_socket_local_url">Local URL</label>
                            <input type="text" class="form-control" name="chat_socket_local_url" id="chat_socket_local_url" value="{{ $mainForm->chat_socket_local_url }}" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="chat_port">Port</label>
                            <input type="text" class="form-control" name="chat_port" id="chat_port" value="{{ $mainForm->chat_port }}" maxlength="255">
                        </div>
                    </fieldset>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="far fa-save"></i> Save
                </button>
            </form>
        </div>

        <div role="tabpanel" class="tab-pane" id="sms">
            <form action="{{ route('settings.update-sms') }}" method="POST">
                @csrf
                <fieldset class="mt-10">
                    @if(auth()->user()->isSuper())
                        <div class="row">
                            <div class="col-lg-3 col-md-5">
                                <div class="form-group">
                                    <label for="sms__regular_provider">Regular Provider</label>
                                    <select class="form-control" name="sms__regular_provider" id="sms__regular_provider">
                                        <option value="">Select Provider</option>
                                        <option value="twilio" {{ $smsForm->sms__regular_provider == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                        <option value="sinch" {{ $smsForm->sms__regular_provider == 'sinch' ? 'selected' : '' }}>Sinch</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-5">
                                <div class="form-group">
                                    <label for="sms__customer_provider">Customer Provider</label>
                                    <select class="form-control" name="sms__customer_provider" id="sms__customer_provider">
                                        <option value="">Select Provider</option>
                                        <option value="twilio" {{ $smsForm->sms__customer_provider == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                        <option value="sinch" {{ $smsForm->sms__customer_provider == 'sinch' ? 'selected' : '' }}>Sinch</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-5">
                                <div class="form-group">
                                    <label for="sms__satellite_regular_provider">Satellite Regular Provider</label>
                                    <select class="form-control" name="sms__satellite_regular_provider" id="sms__satellite_regular_provider">
                                        <option value="">Select Provider</option>
                                        <option value="pivotel" {{ $smsForm->sms__satellite_regular_provider == 'pivotel' ? 'selected' : '' }}>Pivotel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-5">
                                <div class="form-group">
                                    <label for="sms__satellite_customer_provider">Satellite Customer Provider</label>
                                    <select class="form-control" name="sms__satellite_customer_provider" id="sms__satellite_customer_provider">
                                        <option value="">Select Provider</option>
                                        <option value="pivotel" {{ $smsForm->sms__satellite_customer_provider == 'pivotel' ? 'selected' : '' }}>Pivotel</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="form-checkbox-shrink">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sms__stop_not_valid" id="sms__stop_not_valid" value="1" {{ $smsForm->sms__stop_not_valid ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sms__stop_not_valid">
                                            Stop Not Valid
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="form-checkbox-shrink">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sms__stop_satellite_not_valid" id="sms__stop_satellite_not_valid" value="1" {{ $smsForm->sms__stop_satellite_not_valid ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sms__stop_satellite_not_valid">
                                            Stop Satellite Not Valid
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </fieldset>

                <fieldset class="mt-10">
                    <legend>System</legend>

                    <div class="form-checkbox-shrink">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sms__turn_on" id="sms__turn_on" value="1" {{ $smsForm->sms__turn_on ? 'checked' : '' }}>
                            <label class="form-check-label" for="sms__turn_on">
                                SMS Turn On
                            </label>
                        </div>
                    </div>
                    <div class="form-checkbox-shrink">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sms__influencer_phone" id="sms__influencer_phone" value="1" {{ $smsForm->sms__influencer_phone ? 'checked' : '' }}>
                            <label class="form-check-label" for="sms__influencer_phone">
                                Influencer Phone
                            </label>
                        </div>
                    </div>
                    <div class="form-checkbox-shrink">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sms__influencer_bivy" id="sms__influencer_bivy" value="1" {{ $smsForm->sms__influencer_bivy ? 'checked' : '' }}>
                            <label class="form-check-label" for="sms__influencer_bivy">
                                Influencer Bivy
                            </label>
                        </div>
                    </div>
                    <div class="form-checkbox-shrink">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sms__influencer_satellite" id="sms__influencer_satellite" value="1" {{ $smsForm->sms__influencer_satellite ? 'checked' : '' }}>
                            <label class="form-check-label" for="sms__influencer_satellite">
                                Influencer Satellite
                            </label>
                        </div>
                    </div>
                    <div class="form-checkbox-shrink">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sms__influencer_pivotel" id="sms__influencer_pivotel" value="1" {{ $smsForm->sms__influencer_pivotel ? 'checked' : '' }}>
                            <label class="form-check-label" for="sms__influencer_pivotel">
                                Influencer Pivotel
                            </label>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mt-10">
                    <legend>Twilio SMS</legend>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="sms__twilio_sid">SID</label>
                                <input type="text" class="form-control" name="sms__twilio_sid" id="sms__twilio_sid" value="{{ $smsForm->sms__twilio_sid }}" maxlength="255">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="sms__twilio_token">Token</label>
                                <input type="text" class="form-control" name="sms__twilio_token" id="sms__twilio_token" value="{{ $smsForm->sms__twilio_token }}" maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="sms__twilio_from">From</label>
                                <input type="text" class="form-control" name="sms__twilio_from" id="sms__twilio_from" value="{{ $smsForm->sms__twilio_from }}" maxlength="255">
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mt-10">
                    <legend>Sinch SMS</legend>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="sms__sinch_service_plan_id">Service Plan ID</label>
                                <input type="text" class="form-control" name="sms__sinch_service_plan_id" id="sms__sinch_service_plan_id" value="{{ $smsForm->sms__sinch_service_plan_id }}" maxlength="255">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="sms__sinch_bearer_token">Bearer Token</label>
                                <input type="text" class="form-control" name="sms__sinch_bearer_token" id="sms__sinch_bearer_token" value="{{ $smsForm->sms__sinch_bearer_token }}" maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="sms__sinch_from">From</label>
                                <input type="text" class="form-control" name="sms__sinch_from" id="sms__sinch_from" value="{{ $smsForm->sms__sinch_from }}" maxlength="255">
                            </div>
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn-success">
                    <i class="far fa-save"></i> Save
                </button>
            </form>
        </div>

            <div role="tabpanel" class="tab-pane" id="api">
                <form action="{{ route('settings.update-api') }}" method="POST">
                    @csrf
                    <div class="row">
                        <fieldset class="col-md-6 mt-10">
                            <legend>IEX Cloud</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="iex_cloud__secret">Secret</label>
                                        <input type="password" class="form-control" name="iex_cloud__secret" id="iex_cloud__secret" value="{{ $apiForm->iex_cloud__secret }}" maxlength="255">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="iex_cloud__public">Public</label>
                                        <input type="text" class="form-control" name="iex_cloud__public" id="iex_cloud__public" value="{{ $apiForm->iex_cloud__public }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="col-md-6 mt-10">
                            <legend>AlphaVantage</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alphavantage__api_key">API Key</label>
                                        <input type="text" class="form-control" name="alphavantage__api_key" id="alphavantage__api_key" value="{{ $apiForm->alphavantage__api_key }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="col-md-6 mt-10">
                            <legend>MarketStack</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="marketstack__api_key">API Key</label>
                                        <input type="text" class="form-control" name="marketstack__api_key" id="marketstack__api_key" value="{{ $apiForm->marketstack__api_key }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="row">
                        <hr>

                        <fieldset class="col-md-6">
                            <legend>Authorize</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="authorize__name">Name</label>
                                        <input type="text" class="form-control" name="authorize__name" id="authorize__name" value="{{ $apiForm->authorize__name }}" maxlength="255">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="authorize__validation_mode">Validation Mode</label>
                                        <select class="form-control" name="authorize__validation_mode" id="authorize__validation_mode">
                                            <option value="test" {{ $apiForm->authorize__validation_mode == 'test' ? 'selected' : '' }}>Test</option>
                                            <option value="live" {{ $apiForm->authorize__validation_mode == 'live' ? 'selected' : '' }}>Live</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="authorize__transaction_key">Transaction Key</label>
                                        <input type="text" class="form-control" name="authorize__transaction_key" id="authorize__transaction_key" value="{{ $apiForm->authorize__transaction_key }}" maxlength="255">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-checkbox">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="authorize__is_sandbox" id="authorize__is_sandbox" value="1" {{ $apiForm->authorize__is_sandbox ? 'checked' : '' }}>
                                            <label class="form-check-label" for="authorize__is_sandbox">
                                                Is Sandbox
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="col-md-6">
                            <legend>Bivy Stick</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bivy__email">Email</label>
                                        <input type="email" class="form-control" name="bivy__email" id="bivy__email" value="{{ $apiForm->bivy__email }}" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label for="bivy__password">Password</label>
                                        <input type="text" class="form-control" name="bivy__password" id="bivy__password" value="{{ $apiForm->bivy__password }}" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label for="bivy__api_key">API Key</label>
                                        <input type="text" class="form-control" name="bivy__api_key" id="bivy__api_key" value="{{ $apiForm->bivy__api_key }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="col-md-6">
                            <legend>Pivotel</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pivotel__client_id">Client ID</label>
                                        <input type="text" class="form-control" name="pivotel__client_id" id="pivotel__client_id" value="{{ $apiForm->pivotel__client_id }}" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label for="pivotel__client_secret">Client Secret</label>
                                        <input type="text" class="form-control" name="pivotel__client_secret" id="pivotel__client_secret" value="{{ $apiForm->pivotel__client_secret }}" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label for="pivotel__sender_phone">Sender Phone</label>
                                        <input type="text" class="form-control" name="pivotel__sender_phone" id="pivotel__sender_phone" value="{{ $apiForm->pivotel__sender_phone }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="col-md-6">
                            <legend><a href="https://newsapi.org" target="_blank">News API</a></legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="newsapi__api_key">API Key</label>
                                        <input type="text" class="form-control" name="newsapi__api_key" id="newsapi__api_key" value="{{ $apiForm->newsapi__api_key }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="col-md-6">
                            <legend>Apple</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apple__key_id">Key ID</label>
                                        <input type="text" class="form-control" name="apple__key_id" id="apple__key_id" value="{{ $apiForm->apple__key_id }}" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label for="apple__issuer_id">Issuer ID</label>
                                        <input type="text" class="form-control" name="apple__issuer_id" id="apple__issuer_id" value="{{ $apiForm->apple__issuer_id }}" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label for="apple__bundle_id">Bundle ID</label>
                                        <input type="text" class="form-control" name="apple__bundle_id" id="apple__bundle_id" value="{{ $apiForm->apple__bundle_id }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="col-md-6">
                            <legend>OpenAI</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="openai_bearer_key">Bearer Key</label>
                                        <input type="text" class="form-control" name="openai_bearer_key" id="openai_bearer_key" value="{{ $apiForm->openai_bearer_key }}" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label for="openai_chat_model">Chat Model</label>
                                        <input type="text" class="form-control" name="openai_chat_model" id="openai_chat_model" value="{{ $apiForm->openai_chat_model }}" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="far fa-save"></i> Save
                    </button>
                </form>
            </div>

        <div role="tabpanel" class="tab-pane" id="app">
            <form action="{{ route('settings.update-app') }}" method="POST">
                @csrf
                <fieldset class="mt-10">
                    <legend>App</legend>

                    <div class="row">
                        <div class="col-lg-3 col-md-5">
                            <div class="form-group">
                                <label for="app__android_version_strore">Android Version Store</label>
                                <input type="text" class="form-control" name="app__android_version_strore" id="app__android_version_strore" value="{{ $appForm->app__android_version_strore }}" maxlength="255">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-5">
                            <div class="form-group">
                                <label for="app__apple_version_strore">Apple Version Store</label>
                                <input type="text" class="form-control" name="app__apple_version_strore" id="app__apple_version_strore" value="{{ $appForm->app__apple_version_strore }}" maxlength="255">
                            </div>
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn-success">
                    <i class="far fa-save"></i> Save
                </button>
            </form>
        </div>
    </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
    if (hash) {
        $('#JS__settings_tab.nav-tabs a[href="#' + hash + '"]').tab('show');
    }

    // Change hash for page-reload
    $('#JS__settings_tab.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    })

    $('#JS__settings_tab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
})
</script>

<style>
.mt-10 {
    margin-top: 10px;
}
.form-checkbox-shrink {
    margin-bottom: 10px;
}
.form-checkbox {
    margin-bottom: 10px;
}
.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}
.nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}
.tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    padding: 15px;
}
fieldset {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 20px;
}
legend {
    width: auto;
    padding: 0 10px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: bold;
}
</style>
@endsection
