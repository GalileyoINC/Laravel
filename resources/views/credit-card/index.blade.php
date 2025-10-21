@extends('layouts.app')

@section('title', 'Credit Cards - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Credit Cards</h3>
                </div>
                <div class="panel-body">
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <div class="mb-3">
                        <a href="{{ route('credit-card.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <x-table-filter 
                        :title="'Credit Cards'" 
                        :data="$creditCards"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::textColumn('First Name'),
                            TableFilterHelper::textColumn('Last Name'),
                            TableFilterHelper::textColumn('Card Number'),
                            TableFilterHelper::textColumn('Phone'),
                            TableFilterHelper::selectColumn('Type', [
                                'Visa' => 'Visa',
                                'MasterCard' => 'MasterCard',
                                'American Express' => 'American Express',
                                'Discover' => 'Discover',
                            ]),
                            TableFilterHelper::textColumn('Expiration'),
                            TableFilterHelper::textColumn('Gateway Profile ID'),
                            TableFilterHelper::textColumn('Created At'),
                            TableFilterHelper::textColumn('Updated At'),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-1'),
                        ]"
                    >
                        @forelse($creditCards as $card)
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ is_array($card) ? ($card['id'] ?? '') : ($card->id ?? '') }}</td>
                                <td @dataColumn(1)>
                                    @php $user = is_array($card) ? ($card['user'] ?? null) : ($card->user ?? null); @endphp
                                    @if(is_object($user))
                                        <a href="{{ route('user.show', $user) }}">{{ $user->first_name }}</a>
                                    @else
                                        {{ is_array($card) ? ($card['first_name'] ?? '') : ($card->first_name ?? '') }}
                                    @endif
                                </td>
                                <td @dataColumn(2)>
                                    @if(is_object($user))
                                        <a href="{{ route('user.show', $user) }}">{{ $user->last_name }}</a>
                                    @else
                                        {{ is_array($card) ? ($card['last_name'] ?? '') : ($card->last_name ?? '') }}
                                    @endif
                                </td>
                                <td @dataColumn(3)>{{ is_array($card) ? ($card['num'] ?? '') : ($card->num ?? '') }}</td>
                                <td @dataColumn(4)>{{ is_array($card) ? ($card['phone'] ?? '') : ($card->phone ?? '') }}</td>
                                <td @dataColumn(5) @dataValue(is_array($card) ? ($card['type'] ?? '') : ($card->type ?? ''))>
                                    <span class="label label-info">{{ is_array($card) ? ($card['type'] ?? '') : ($card->type ?? '') }}</span>
                                </td>
                                <td @dataColumn(6)>{{ (is_array($card) ? ($card['expiration_year'] ?? '') : ($card->expiration_year ?? '')) }} / {{ (is_array($card) ? ($card['expiration_month'] ?? '') : ($card->expiration_month ?? '')) }}</td>
                                <td @dataColumn(7)>{{ (is_array($card) ? ($card['anet_customer_payment_profile_id'] ?? '') : ($card->anet_customer_payment_profile_id ?? '')) ?: '-' }}</td>
                                <td @dataColumn(8)>
                                    @php $cAt = is_array($card) ? ($card['created_at'] ?? null) : ($card->created_at ?? null); @endphp
                                    @if($cAt instanceof \Illuminate\Support\Carbon)
                                        {{ $cAt->format('M d, Y') }}
                                    @elseif(!empty($cAt))
                                        {{ \Illuminate\Support\Carbon::parse($cAt)->format('M d, Y') }}
                                    @else
                                        
                                    @endif
                                </td>
                                <td @dataColumn(9)>
                                    @php $uAt = is_array($card) ? ($card['updated_at'] ?? null) : ($card->updated_at ?? null); @endphp
                                    @if($uAt instanceof \Illuminate\Support\Carbon)
                                        {{ $uAt->format('M d, Y') }}
                                    @elseif(!empty($uAt))
                                        {{ \Illuminate\Support\Carbon::parse($uAt)->format('M d, Y') }}
                                    @else
                                        
                                    @endif
                                </td>
                                <td @dataColumn(10)>
                                    <div class="btn-group">
                                        @php $cardId = is_array($card) ? ($card['id'] ?? null) : ($card->id ?? null); @endphp
                                        @if($cardId)
                                        <a href="{{ route('credit-card.show', ['credit_card' => $cardId]) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No credit cards found.</td>
                            </tr>
                        @endforelse
                    </x-table-filter>
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
}
.panel-body {
    padding: 15px;
}
.grid__id {
    width: 60px;
}
.action-column-1 {
    width: 100px;
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
.table-responsive {
    overflow-x: auto;
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
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.btn-group {
    display: inline-flex;
    vertical-align: middle;
}
.btn-group .btn {
    position: relative;
    flex: 1 1 auto;
}
.btn-group .btn + .btn {
    margin-left: -1px;
}
.btn-group .btn:first-child {
    margin-left: 0;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-inline .form-group {
    display: flex;
    flex: 0 0 auto;
    flex-flow: row wrap;
    align-items: center;
    margin-bottom: 0;
}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
@endsection
