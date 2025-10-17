@if(!empty($json['features']))
    @foreach($json['features'] as $feature)
        <dl class="dl-horizontal">
            <dt>Status</dt><dd>{{ $feature['properties']['status'] ?? 'N/A' }}</dd>
            <dt>Message Type</dt><dd>{{ $feature['properties']['messageType'] ?? 'N/A' }}</dd>
            <dt>Category</dt><dd>{{ $feature['properties']['category'] ?? 'N/A' }}</dd>
            <dt>Severity</dt><dd>{{ $feature['properties']['severity'] ?? 'N/A' }}</dd>
            <dt>Certainty</dt><dd>{{ $feature['properties']['certainty'] ?? 'N/A' }}</dd>
            <dt>Urgency</dt><dd>{{ $feature['properties']['urgency'] ?? 'N/A' }}</dd>
            <dt>Event</dt><dd>{{ $feature['properties']['event'] ?? 'N/A' }}</dd>
            <dt>Sender</dt><dd>{{ $feature['properties']['sender'] ?? 'N/A' }}</dd>
            <dt>Sender Name</dt><dd>{{ $feature['properties']['senderName'] ?? 'N/A' }}</dd>
            <dt>Response</dt><dd>{{ $feature['properties']['response'] ?? 'N/A' }}</dd>
        </dl>
        <dl class="">
            <dt>Headline</dt><dd>{{ $feature['properties']['headline'] ?? 'N/A' }}</dd>
            <dt>Description</dt><dd>{{ $feature['properties']['description'] ?? 'N/A' }}</dd>
            <dt>Instruction</dt><dd>{{ $feature['properties']['instruction'] ?? 'N/A' }}</dd>
        </dl>
        <hr>
    @endforeach
@endif

<style>
.dl-horizontal {
    margin-bottom: 1rem;
}
.dl-horizontal dt {
    float: left;
    width: 160px;
    overflow: hidden;
    clear: left;
    text-align: right;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: 600;
}
.dl-horizontal dd {
    margin-left: 180px;
}
dl {
    margin-bottom: 1rem;
}
dt {
    font-weight: 600;
}
dd {
    margin-left: 0;
}
hr {
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
