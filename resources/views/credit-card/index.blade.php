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
                    <!-- Summary -->
                    <div class="summary" style="margin-bottom:10px;">
                        @if($creditCards instanceof \Illuminate\Contracts\Pagination\Paginator && $creditCards->total() > 0)
                            Showing <b>{{ $creditCards->firstItem() }}-{{ $creditCards->lastItem() }}</b> of <b>{{ $creditCards->total() }}</b> items.
                        @elseif(is_array($creditCards) && count($creditCards) > 0)
                            Showing <b>1-{{ count($creditCards) }}</b> of <b>{{ count($creditCards) }}</b> items.
                        @else
                            Showing <b>0-0</b> of <b>0</b> items.
                        @endif
                    </div>

                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('credit-card.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <form method="GET" id="filters-form"></form>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Card Number</th>
                                    <th>Phone</th>
                                    <th>Type</th>
                                    <th>Expiration</th>
                                    <th>Gateway Profile ID</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th class="action-column-1">Actions</th>
                                </tr>
                                <tr class="filters">
                                    <td>
                                        <input type="text" name="search" class="form-control" form="filters-form" placeholder="Search..." value="{{ request('search') }}">
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <select name="type" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            <option value="Visa" {{ request('type') == 'Visa' ? 'selected' : '' }}>Visa</option>
                                            <option value="MasterCard" {{ request('type') == 'MasterCard' ? 'selected' : '' }}>MasterCard</option>
                                            <option value="American Express" {{ request('type') == 'American Express' ? 'selected' : '' }}>American Express</option>
                                            <option value="Discover" {{ request('type') == 'Discover' ? 'selected' : '' }}>Discover</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="expiration_year" class="form-control" form="filters-form">
                                            <option value=""></option>
                                            @foreach($years as $year)
                                                <option value="{{ $year }}" {{ request('expiration_year') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td></td>
                                    <td>
                                        <input type="date" name="created_at_from" class="form-control" form="filters-form" value="{{ request('created_at_from') }}">
                                    </td>
                                    <td>
                                        <input type="date" name="updated_at_from" class="form-control" form="filters-form" value="{{ request('updated_at_from') }}">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" form="filters-form">Filter</button>
                                        <a href="{{ route('credit-card.index') }}" class="btn btn-default ml-2">Clear</a>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($creditCards as $card)
                                    <tr>
                                        <td>{{ is_array($card) ? ($card['id'] ?? '') : ($card->id ?? '') }}</td>
                                        <td>
                                            @php $user = is_array($card) ? ($card['user'] ?? null) : ($card->user ?? null); @endphp
                                            @if(is_object($user))
                                                <a href="{{ route('user.show', $user) }}">{{ $user->first_name }}</a>
                                            @else
                                                {{ is_array($card) ? ($card['first_name'] ?? '') : ($card->first_name ?? '') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(is_object($user))
                                                <a href="{{ route('user.show', $user) }}">{{ $user->last_name }}</a>
                                            @else
                                                {{ is_array($card) ? ($card['last_name'] ?? '') : ($card->last_name ?? '') }}
                                            @endif
                                        </td>
                                        <td>{{ is_array($card) ? ($card['num'] ?? '') : ($card->num ?? '') }}</td>
                                        <td>{{ is_array($card) ? ($card['phone'] ?? '') : ($card->phone ?? '') }}</td>
                                        <td>
                                            <span class="label label-info">{{ is_array($card) ? ($card['type'] ?? '') : ($card->type ?? '') }}</span>
                                        </td>
                                        <td>{{ is_array($card) ? ($card['expiration_year'] ?? '') : ($card->expiration_year ?? '') }} / {{ is_array($card) ? ($card['expiration_month'] ?? '') : ($card->expiration_month ?? '') }}</td>
                                        <td>{{ (is_array($card) ? ($card['anet_customer_payment_profile_id'] ?? '') : ($card->anet_customer_payment_profile_id ?? '')) ?: '-' }}</td>
                                        <td>
                                            @php $cAt = is_array($card) ? ($card['created_at'] ?? null) : ($card->created_at ?? null); @endphp
                                            @if($cAt instanceof \Illuminate\Support\Carbon)
                                                {{ $cAt->format('M d, Y') }}
                                            @elseif(!empty($cAt))
                                                {{ \Illuminate\Support\Carbon::parse($cAt)->format('M d, Y') }}
                                            @else
                                                
                                            @endif
                                        </td>
                                        <td>
                                            @php $uAt = is_array($card) ? ($card['updated_at'] ?? null) : ($card->updated_at ?? null); @endphp
                                            @if($uAt instanceof \Illuminate\Support\Carbon)
                                                {{ $uAt->format('M d, Y') }}
                                            @elseif(!empty($uAt))
                                                {{ \Illuminate\Support\Carbon::parse($uAt)->format('M d, Y') }}
                                            @else
                                                
                                            @endif
                                        </td>
                                        <td>
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
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($creditCards instanceof \Illuminate\Contracts\Pagination\Paginator)
                        <div class="d-flex justify-content-center">
                            {{ $creditCards->appends(request()->query())->links() }}
                        </div>
                    @endif
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
