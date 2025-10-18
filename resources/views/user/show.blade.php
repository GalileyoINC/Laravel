@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <span>
            @if($user->is_test)
                <span class="label label-danger">TEST</span>
            @endif
            @if($user->is_influencer)
                <span class="label label-info">Influencer</span>
            @endif
            Customer #{{ $user->id }}
        </span>
        <div class="pull-right">
            <a href="{{ route('user.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-pen-fancy"></i> Update
            </a>
        </div>
    </div>

    <div class="panel-body">
        @if($user->is_influencer && !$user->influencer_verified_at)
            <div class="alert alert-info">
                A user is an influencer to be verified
                <form action="{{ route('user.influencer-verified', $user) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Verified</button>
                </form>
                <form action="{{ route('user.influencer-refused', $user) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">Refused</button>
                </form>
            </div>
        @endif

        <ul class="nav nav-tabs" role="tablist" id="itemTab">
            <li role="presentation" class="active">
                <a href="#base" aria-controls="base" role="tab" data-toggle="tab">Base</a>
            </li>
            <li role="presentation">
                <a href="#phones" aria-controls="phones" role="tab" data-toggle="tab">Phones</a>
            </li>
            <li role="presentation">
                <a href="#subscriptions" aria-controls="subscriptions" role="tab" data-toggle="tab">Subscriptions</a>
            </li>
            <li role="presentation">
                <a href="#credit_cards" aria-controls="credit_cards" role="tab" data-toggle="tab">Credit Cards</a>
            </li>
        </ul>

        <br>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="base">
                <div class="row">
                    <div class="col-lg-6">
                        <table class="table table-striped table-bordered detail-view">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>First Name</th>
                                    <td>{{ $user->first_name }}</td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td>{{ $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($user->status == 1)
                                            <span class="label label-success">Active</span>
                                        @else
                                            <span class="label label-danger">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $user->country ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>State</th>
                                    <td>{{ $user->state ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>ZIP</th>
                                    <td>{{ $user->zip ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $user->city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Is Influencer</th>
                                    <td>
                                        @if($user->is_influencer)
                                            <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times"></i> No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Is Test</th>
                                    <td>
                                        @if($user->is_test)
                                            <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times"></i> No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Bonus Points</th>
                                    <td>{{ $user->bonus_point ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $user->created_at->format('M d, Y h:i a') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $user->updated_at->format('M d, Y h:i a') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if(auth()->user()->is_superlogin ?? false)
                            <form action="{{ route('user.login-as', $user) }}" method="POST" target="_blank">
                                @csrf
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-sign-in-alt fa-fw"></i> Login
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="col-lg-6">
                        <h4>Quick Actions</h4>
                        <div class="btn-group-vertical" style="width: 100%;">
                            <a href="{{ route('user.credit', $user) }}" class="btn btn-default">
                                <i class="fas fa-coins"></i> Apply Credit
                            </a>
                            <form action="{{ route('user.remove-credit', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-default" style="width: 100%;">
                                    <i class="fas fa-minus-circle"></i> Remove Credit
                                </button>
                            </form>
                            <a href="{{ route('user.transaction-list', $user) }}" class="btn btn-default">
                                <i class="fas fa-list"></i> Show Transactions
                            </a>
                            <a href="{{ route('user.gateway-profile', $user) }}" class="btn btn-default">
                                <i class="fas fa-user-circle"></i> Show Gateway Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="phones">
                <h4>Phone Numbers</h4>
                @if($user->phoneNumbers && $user->phoneNumbers->count() > 0)
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Type</th>
                                <th>Valid</th>
                                <th>Primary</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->phoneNumbers as $phone)
                                <tr>
                                    <td>{{ $phone->number }}</td>
                                    <td>{{ $phone->type_name ?? 'N/A' }}</td>
                                    <td>
                                        @if($phone->is_valid)
                                            <span class="text-success"><i class="fas fa-check"></i></span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($phone->is_primary)
                                            <span class="text-success"><i class="fas fa-check"></i></span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($phone->is_active)
                                            <span class="text-success"><i class="fas fa-check"></i></span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No phone numbers found.</p>
                @endif
            </div>

            <div role="tabpanel" class="tab-pane" id="subscriptions">
                <h4>Subscriptions</h4>
                @if($user->subscriptions && $user->subscriptions->count() > 0)
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->id }}</td>
                                    <td>{{ $subscription->title ?? 'N/A' }}</td>
                                    <td>
                                        @if($subscription->is_active)
                                            <span class="label label-success">Active</span>
                                        @else
                                            <span class="label label-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $subscription->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No subscriptions found.</p>
                @endif
            </div>

            <div role="tabpanel" class="tab-pane" id="credit_cards">
                <h4>Credit Cards</h4>
                @if($user->creditCards && $user->creditCards->count() > 0)
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Last 4</th>
                                <th>Type</th>
                                <th>Expires</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->creditCards as $card)
                                <tr>
                                    <td>****{{ $card->last_four ?? 'N/A' }}</td>
                                    <td>{{ $card->card_type ?? 'N/A' }}</td>
                                    <td>{{ $card->expiration_date ?? 'N/A' }}</td>
                                    <td>
                                        @if($card->is_active)
                                            <span class="text-success"><i class="fas fa-check"></i></span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No credit cards found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
