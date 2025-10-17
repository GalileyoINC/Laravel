@extends('web.layouts.app')

@section('title', 'Credit Card Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Credit Card #{{ $creditCard->id }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('web.credit-card.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Credit Card Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $creditCard->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>User ID</th>
                                        <td>{{ $creditCard->id_user }}</td>
                                    </tr>
                                    <tr>
                                        <th>First Name</th>
                                        <td>{{ $creditCard->first_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Name</th>
                                        <td>{{ $creditCard->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Card Number</th>
                                        <td>{{ $creditCard->num }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $creditCard->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td>
                                            <span class="label label-info">{{ $creditCard->type }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Expiration Year</th>
                                        <td>{{ $creditCard->expiration_year }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expiration Month</th>
                                        <td>{{ $creditCard->expiration_month }}</td>
                                    </tr>
                                    <tr>
                                        <th>Is Active</th>
                                        <td>
                                            @if($creditCard->is_active)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $creditCard->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $creditCard->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Agree to Receive</th>
                                        <td>
                                            @if($creditCard->is_agree_to_receive)
                                                <i class="fas fa-check text-success"></i> Yes
                                            @else
                                                <i class="fas fa-times text-danger"></i> No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Gateway Profile ID</th>
                                        <td>{{ $creditCard->anet_customer_payment_profile_id ?: '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- User Information -->
                            @if($creditCard->user)
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">User Information</h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><strong>Name:</strong> {{ $creditCard->user->first_name }} {{ $creditCard->user->last_name }}</p>
                                        <p><strong>Email:</strong> {{ $creditCard->user->email }}</p>
                                        <p><strong>User ID:</strong> {{ $creditCard->user->id }}</p>
                                        <a href="{{ route('web.user.show', $creditCard->user) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-user"></i> View User
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Gateway Actions -->
                            @if($creditCard->anet_customer_payment_profile_id)
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Gateway Actions</h4>
                                    </div>
                                    <div class="panel-body">
                                        <a href="{{ route('web.credit-card.get-gateway-profile', $creditCard) }}" class="btn btn-admin">
                                            <i class="fas fa-credit-card"></i> Show Profile (API request)
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.panel-heading-actions {
    display: flex;
    gap: 10px;
}
.panel-body {
    padding: 15px;
}
.panel-info {
    border: 1px solid #bce8f1;
}
.panel-info .panel-heading {
    background-color: #d9edf7;
    border-bottom: 1px solid #bce8f1;
}
.panel-warning {
    border: 1px solid #faebcc;
}
.panel-warning .panel-heading {
    background-color: #fcf8e3;
    border-bottom: 1px solid #faebcc;
}
.label {
    display: inline-block;
    padding: 0.25em 0.6em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}
.label-info {
    background-color: #17a2b8;
    color: #fff;
}
.text-success {
    color: #28a745;
}
.text-danger {
    color: #dc3545;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-admin {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
</style>
@endsection
