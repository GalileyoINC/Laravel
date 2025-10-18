<h4>Phone Numbers</h4>

@if($model->phoneNumbers && $model->phoneNumbers->count() > 0)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Number</th>
                <th>Type</th>
                <th>Valid</th>
                <th>Primary</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($model->phoneNumbers as $phone)
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
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('phone-number.show', $phone) }}" class="btn btn-xs btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted">No phone numbers found.</p>
@endif
