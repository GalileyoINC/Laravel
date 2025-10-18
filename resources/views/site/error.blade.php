@extends('layouts.app')

@section('title', 'Error - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center">
                <div class="error mx-auto" data-text="ERROR">
                    <p class="m-0">ERROR</p>
                </div>
                <p class="text-gray-500 mb-5">An error occurred</p>
                <a href="{{ route('site.index') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.error {
    font-size: 7rem;
    position: relative;
    line-height: 1;
    width: 12.5rem;
}
.error::before {
    content: attr(data-text);
    position: absolute;
    left: 2px;
    top: 2px;
    width: 100%;
    color: #5a5c69;
    overflow: hidden;
    clip: rect(0, 900px, 0, 0);
    animation: noise-anim 2s infinite linear alternate-reverse;
}
.error::after {
    content: attr(data-text);
    position: absolute;
    left: -2px;
    top: -2px;
    width: 100%;
    color: #5a5c69;
    overflow: hidden;
    clip: rect(0, 900px, 0, 0);
    animation: noise-anim-2 3s infinite linear alternate-reverse;
}
@keyframes noise-anim {
    0% {
        clip: rect(31px, 9999px, 94px, 0);
    }
    5% {
        clip: rect(112px, 9999px, 76px, 0);
    }
    10% {
        clip: rect(85px, 9999px, 77px, 0);
    }
    15% {
        clip: rect(27px, 9999px, 97px, 0);
    }
    20% {
        clip: rect(64px, 9999px, 98px, 0);
    }
    25% {
        clip: rect(111px, 9999px, 114px, 0);
    }
    30% {
        clip: rect(48px, 9999px, 53px, 0);
    }
    35% {
        clip: rect(98px, 9999px, 77px, 0);
    }
    40% {
        clip: rect(113px, 9999px, 44px, 0);
    }
    45% {
        clip: rect(23px, 9999px, 99px, 0);
    }
    50% {
        clip: rect(82px, 9999px, 73px, 0);
    }
    55% {
        clip: rect(79px, 9999px, 85px, 0);
    }
    60% {
        clip: rect(37px, 9999px, 115px, 0);
    }
    65% {
        clip: rect(113px, 9999px, 80px, 0);
    }
    70% {
        clip: rect(23px, 9999px, 96px, 0);
    }
    75% {
        clip: rect(108px, 9999px, 44px, 0);
    }
    80% {
        clip: rect(79px, 9999px, 85px, 0);
    }
    85% {
        clip: rect(28px, 9999px, 99px, 0);
    }
    90% {
        clip: rect(83px, 9999px, 45px, 0);
    }
    95% {
        clip: rect(37px, 9999px, 115px, 0);
    }
    100% {
        clip: rect(113px, 9999px, 80px, 0);
    }
}
@keyframes noise-anim-2 {
    0% {
        clip: rect(65px, 9999px, 119px, 0);
    }
    5% {
        clip: rect(25px, 9999px, 118px, 0);
    }
    10% {
        clip: rect(112px, 9999px, 29px, 0);
    }
    15% {
        clip: rect(25px, 9999px, 35px, 0);
    }
    20% {
        clip: rect(97px, 9999px, 82px, 0);
    }
    25% {
        clip: rect(115px, 9999px, 98px, 0);
    }
    30% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    35% {
        clip: rect(97px, 9999px, 35px, 0);
    }
    40% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    45% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    50% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    55% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    60% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    65% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    70% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    75% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    80% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    85% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    90% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    95% {
        clip: rect(25px, 9999px, 25px, 0);
    }
    100% {
        clip: rect(25px, 9999px, 25px, 0);
    }
}
.text-gray-500 {
    color: #6c757d !important;
}
</style>
@endsection
