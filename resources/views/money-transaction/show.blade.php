@extends('layouts.app')

@section('title', 'Money Transaction Details - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Transaction #{{ $transaction->id }}</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <th class="w-25">User</th>
                                    <td>
                                        @if($transaction->user)
                                            <a href="{{ route('user.show', $transaction->user->id) }}">
                                                {{ $transaction->user->first_name }} {{ $transaction->user->last_name }} ({{ $transaction->user->id }})
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Invoice</th>
                                    <td>
                                        @if($transaction->invoice)
                                            <a href="{{ route('invoice.show', $transaction->invoice) }}">#{{ $transaction->invoice->id }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Credit Card</th>
                                    <td>
                                        @if($transaction->creditCard)
                                            {{ $transaction->creditCard->type ?? '' }} {{ $transaction->creditCard->num ?? '' }} ({{ $transaction->creditCard->expiration_year ?? '' }}/{{ $transaction->creditCard->expiration_month ?? '' }})
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Transaction Type</th>
                                    <td>{{ $transaction->transaction_type }}</td>
                                </tr>
                                <tr>
                                    <th>Transaction ID</th>
                                    <td>{{ $transaction->transaction_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Success</th>
                                    <td>{!! $transaction->is_success ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>' !!}</td>
                                </tr>
                                <tr>
                                    <th>Void</th>
                                    <td>{!! $transaction->is_void ? '<span class="label label-warning">Yes</span>' : '<span class="label label-default">No</span>' !!}</td>
                                </tr>
                                <tr>
                                    <th>Test</th>
                                    <td>{!! $transaction->is_test ? '<span class="label label-info">Yes</span>' : '<span class="label label-default">No</span>' !!}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>{{ number_format($transaction->total, 2) }} {{ config('app.currency', '$') }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $transaction->created_at ? $transaction->created_at->format('M d, Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $transaction->updated_at ? $transaction->updated_at->format('M d, Y H:i') : '-' }}</td>
                                </tr>
                                @if($transaction->error)
                                <tr>
                                    <th>Error</th>
                                    <td><pre style="white-space: pre-wrap;">{{ $transaction->error }}</pre></td>
                                </tr>
                                @endif
                                @if($transaction->note)
                                <tr>
                                    <th>Note</th>
                                    <td>{{ $transaction->note }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('money-transaction.index') }}" class="btn btn-default">Back</a>
                        @if($transaction->canBeRefund())
                            <a href="{{ route('money-transaction.refund', $transaction->id) }}" class="btn btn-danger JS__load_in_modal">Refund</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
