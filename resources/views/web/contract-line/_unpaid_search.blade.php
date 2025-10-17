<div class="">
    <form method="GET" action="{{ route('web.contract-line.unpaid') }}" class="form-inline">
        @csrf
        Unpaid bills for the last 
        <input type="number" name="exp_date" class="form-control" style="width:70px" value="{{ $expDateDays ?? 30 }}">
        days

        <div class="form-group ml-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<style>
.form-inline {
    display: flex;
    flex-flow: row wrap;
    align-items: center;
}
.form-group {
    margin-bottom: 0;
}
.form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    text-decoration: none;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn:hover {
    text-decoration: none;
}
.btn-primary:hover {
    color: #fff;
    background-color: #0056b3;
    border-color: #004085;
}
</style>
