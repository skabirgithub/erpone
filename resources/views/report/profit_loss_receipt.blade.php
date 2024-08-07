{{-- @extends('layouts.admin') --}}
@php
    $settings = Utility::settings();
    $color = (!empty($setting['color'])) ? $setting['color'] : 'theme-3';
@endphp
<html lang="en" dir="{{$settings == 'on'?'rtl':''}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" >

    <title>{{env('APP_NAME')}} - Profit & Loss</title>
    @if (isset($settings['SITE_RTL'] ) && $settings['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css')}}" id="main-style-link">
    @endif


</head>

<script src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#filter").click(function() {
                $("#show_filter").toggle();
            });
        });
    </script>

<script>
    window.print();
    window.onafterprint = back;

    function back() {
        window.close();
        window.history.back();
    }
</script>


<body class="{{ $color }}">

    <div class="mt-4">

        @php
            $authUser = \Auth::user()->creatorId();
            $user = App\Models\User::find($authUser);
        @endphp
    <div class="row justify-content-center" id="printableArea">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="account-main-title mb-5">
                        <h5>{{ 'Profit & Loss of ' . $user->name . ' as of ' . $filter['startDateRange'] . ' to ' . $filter['endDateRange'] }}
                            </h4>
                    </div>
                    <div
                        class="aacount-title d-flex align-items-center justify-content-between border-top border-bottom py-2">
                        <h6 class="mb-0">{{ __('Account') }}</h6>
                        <h6 class="mb-0 text-center">{{ __('Account Code') }}</h6>
                        <h6 class="mb-0 text-end">{{ __('Total') }}</h6>

                    </div>
                    @php
                        $totalIncome = 0;
                        $netProfit = 0;
                        $totalCosts = 0;
                        $grossProfit= 0;
                    @endphp

                    @foreach ($chartAccounts as $accounts)
                        @if ($accounts['Type'] == 'Income')
                            <div class="account-main-inner border-bottom py-2">
                                <p class="fw-bold mb-2">{{ $accounts['Type'] }}</p>

                                @foreach ($accounts['account'] as $key => $record)
                                    <div class="account-inner d-flex align-items-center justify-content-between">
                                        @if (!preg_match('/\btotal\b/i', $record['account_name']))
                                            <p class="mb-2 ps-3"><a
                                                    href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                    class="text-primary">{{ $record['account_name'] }}</a>
                                            </p>
                                        @else
                                            <p class="fw-bold mb-2"><a
                                                    href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                    class="text-dark">{{ $record['account_name'] }}</a>
                                        @endif
                                        <p class="mb-2 text-center">{{ $record['account_code'] }}</p>
                                        <p class="text-primary mb-2 float-end text-end">
                                            {{ \Auth::user()->priceFormat($record['netAmount']) }}</p>
                                    </div>

                                    @php
                                        if ($record['account_name'] === 'Total Income') {
                                            $totalIncome = $record['netAmount'];
                                        }

                                        if ($record['account_name'] == 'Total Costs of Goods Sold') {
                                            $totalCosts = $record['netAmount'];
                                        }
                                        $grossProfit = $totalIncome - $totalCosts;
                                    @endphp
                                @endforeach
                            </div>
                        @endif
                        @if ($accounts['Type'] == 'Costs of Goods Sold')
                        <div class="account-main-inner border-bottom py-2">
                            <p class="fw-bold mb-2">{{ $accounts['Type'] }}</p>

                            @foreach ($accounts['account'] as $key => $record)
                            @php
                                if($record['netAmount'] > 0)
                                {
                                    $netAmount = $record['netAmount'];
                                }
                                else {
                                    $netAmount = -$record['netAmount'];
                                }
                            @endphp
                                <div class="account-inner d-flex align-items-center justify-content-between">
                                    @if (!preg_match('/\btotal\b/i', $record['account_name']))
                                        <p class="mb-2 ps-3"><a
                                                href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                class="text-primary">{{ $record['account_name'] }}</a>
                                        </p>
                                    @else
                                        <p class="fw-bold mb-2"><a
                                                href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                class="text-dark">{{ $record['account_name'] }}</a>
                                    @endif
                                    <p class="mb-2 text-center">{{ $record['account_code'] }}</p>
                                    <p class="text-primary mb-2 float-end text-end">
                                        {{ \Auth::user()->priceFormat($netAmount) }}</p>
                                </div>

                                @php
                                    if ($record['account_name'] === 'Total Income') {
                                        $totalIncome = $record['netAmount'];
                                    }

                                    if ($record['account_name'] == 'Total Costs of Goods Sold') {
                                        $totalCosts = $netAmount;
                                    }
                                    $grossProfit = $totalIncome - ($totalCosts);
                                @endphp
                            @endforeach
                        </div>
                    @endif
                    @endforeach

                    @if($grossProfit > 0)
                    <div class="account-inner d-flex align-items-center justify-content-between border-bottom">
                        <p></p>
                        <p class="fw-bold mb-2 text-center">{{ __('Gross Profit') }}</p>
                        <p class="text-primary mb-2 float-end text-end">
                            {{ \Auth::user()->priceFormat($grossProfit) }}</p>
                    </div>
                    @endif
                    @foreach ($chartAccounts as $accounts)
                        @if ($accounts['Type'] == 'Expenses')
                            <div class="account-main-inner border-bottom py-2">
                                <p class="fw-bold mb-2">{{ $accounts['Type'] }}</p>

                                @foreach ($accounts['account'] as $key => $record)
                                @php
                                if($record['netAmount'] > 0)
                                {
                                    $netAmount = $record['netAmount'];
                                }
                                else {
                                    $netAmount = -$record['netAmount'];
                                }
                            @endphp
                                    <div class="account-inner d-flex align-items-center justify-content-between">
                                        @if (!preg_match('/\btotal\b/i', $record['account_name']))
                                            <p class="mb-2 ps-3"><a
                                                    href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                    class="text-primary">{{ $record['account_name'] }}</a>
                                            </p>
                                        @else
                                            <p class="fw-bold mb-2"><a
                                                    href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                    class="text-dark">{{ $record['account_name'] }}</a>
                                        @endif
                                        <p class="mb-2 text-center">{{ $record['account_code'] }}</p>
                                        <p class="text-primary mb-2 float-end text-end">
                                            {{ \Auth::user()->priceFormat($netAmount) }}</p>
                                    </div>

                                    @php
                                        if ($record['account_name'] === 'Total Expenses') {
                                            $totalIncome = $record['netAmount'];
                                            $netProfit = $grossProfit - $netAmount;
                                        }
                                    @endphp

                                @endforeach
                            </div>

                            <div class="account-inner d-flex align-items-center justify-content-between border-bottom">
                                <p></p>
                                <p class="fw-bold mb-2 text-center">{{ __('Net Profit/Loss') }}</p>
                                <p class="text-primary mb-2 float-end text-end">
                                    {{ \Auth::user()->priceFormat($netProfit) }}</p>
                                </div>
                                @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>

