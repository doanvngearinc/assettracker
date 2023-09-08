<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ trans('general.assigned_to', ['name' => $show_user->present()->fullName()]) }} - {{ date('Y-m-d H:i',
        time()) }}</title>

    <!-- DataTable -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: "Arial, Helvetica", sans-serif;
        }

        table.inventory {
            border: solid #000;
            border-width: 1px 1px 1px 1px;
            width: 100%;
        }

        @page {
            size: A4;
        }

        table.inventory th,
        table.inventory td {
            border: solid #000;
            border-width: 0 1px 1px 0;
            padding: 3px;
            font-size: 12px;
        }

        .print-logo {
            max-height: 40px;
        }

        .text_Location {
            font-size: 13px;
            font-style: oblique;
            font-weight: lighter;
        }

        .head_content {
            margin-top: 30px;
            text-align: center;
            text-transform: uppercase;
        }

        .respons_header {
            font-weight: 700;
        }

        .respons_txt {
            text-align: left;
            font-weight: 300;
        }

        .respons_txt_2 {
            text-align: left;
            padding-top: 23px;
            font-weight: 300;
        }

        .signature {
            display: flex;
            justify-content: space-around;
        }

        @media print {

            .printB,
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                display: none;
            }
        }
    </style>
</head>

<body id="printableArea">

    @if ($snipeSettings->logo_print_assets=='1')
    @if ($snipeSettings->brand == '3')

    <h2>
        @if ($snipeSettings->logo!='')
        <img class="print-logo" src="{{ config('app.url') }}/uploads/{{ $snipeSettings->logo }}">
        @endif
        {{ $snipeSettings->site_name }}
        <p class="text_Location">{{ trans('general.location_company') }}</p>
    </h2>
    <h2 class="head_content">
        {{ trans('general.head_content') }}
    </h2>
    @elseif ($snipeSettings->brand == '2')
    @if ($snipeSettings->logo!='')
    <img class="print-logo" src="{{ config('app.url') }}/uploads/{{ $snipeSettings->logo }}">
    @endif
    @else
    <h2>{{ $snipeSettings->site_name }}</h2>
    @endif
    @endif

    <a class="printB" href="javascript:void(0);" onclick="printPageArea('printableArea')">Print</a>

    <h2>{{ trans('general.general_info') }}</h2>
    {{-- <h3>{{ trans('general.assigned_to', ['name' => $show_user->present()->fullName()]) }} {{
        ($show_user->jobtitle!='' ?
        ' - '.$show_user->jobtitle : '') }}
    </h3> --}}
    <h3>{{ trans('general.taken') }} {{ $show_user->first_name }} {{ $show_user->last_name }}
    </h3>
    <thead>
        <tr>
            <th scope="row">Job detail: </th>
            <th scope=""> {{ $show_user->jobtitle }}</th>
        </tr>
    </thead>
    <br>
    <tbody>
        <tr>
            <th scope="col"> {{ trans('general.department') }}:</th>
            <th scope="col"> {{ $show_user->department->name }} </th>
        </tr>
    </tbody>
    <br>
    <br>
    @if ($assets->count() > 0)
    @php
    $counter = 1;
    @endphp

    <div class="container table-responsive">
        <table id="myTable" class="inventory">
            {{-- <thead>
                <tr>
                    <th colspan="8">{{ trans('general.assets') }}</th>
                </tr>
            </thead> --}}
            <thead>
                <tr>
                    <th style="width: 20px;"></th>
                    <th style="width: 20%;">{{ trans('admin/hardware/table.asset_tag') }}</th>
                    <th style="width: 20%;">{{ trans('general.name') }}</th>
                    <th style="width: 10%;">{{ trans('general.category') }}</th>
                    <th style="width: 20%;">{{ trans('admin/hardware/form.model') }}</th>
                    <th style="width: 20%;">{{ trans('admin/hardware/form.serial') }}</th>
                    <th style="width: 10%;">{{ trans('admin/hardware/table.checkout_date') }}</th>
                    <th data-formatter="imageFormatter" style="width: 20%;">{{ trans('general.signature') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assets as $asset)

                <tr>
                    <td>{{ $counter }}</td>
                    <td>{{ $asset->asset_tag }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->model->category->name }}</td>
                    <td>{{ $asset->model->name }}</td>
                    <td>{{ $asset->serial }}</td>
                    <td>
                        {{ $asset->last_checkout }}</td>
                    <td>
                        @if (($asset->assetlog->first()) && ($asset->assetlog->first()->accept_signature!=''))
                        <img style="width:auto;height:100px;"
                            src="{{ asset('/') }}display-sig/{{ $asset->assetlog->first()->accept_signature }}">
                        @endif
                    </td>
                </tr>
                @if($settings->show_assigned_assets)
                @php
                $assignedCounter = 1;
                @endphp
                @foreach ($asset->assignedAssets as $asset)

                <tr>
                    <td>{{ $counter }}.{{ $assignedCounter }}</td>
                    <td>{{ $asset->asset_tag }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->model->category->name }}</td>
                    <td>{{ $asset->model->name }}</td>
                    <td>{{ $asset->serial }}</td>
                    <td>{{ $asset->last_checkout }}</td>
                    <td><img style="width:auto;height:100px;"
                            src="{{ asset('/') }}display-sig/{{ $asset->assetlog->first()->accept_signature }}"></td>
                </tr>
                @php
                $assignedCounter++
                @endphp
                @endforeach
                @endif
                @php
                $counter++
                @endphp
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if ($licenses->count() > 0)
    <table class="inventory">
        <thead>
            <tr>
                <th colspan="4">{{ trans('general.licenses') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="width: 20px;"></th>
                <th style="width: 40%;">{{ trans('general.name') }}</th>
                <th style="width: 50%;">{{ trans('admin/licenses/form.license_key') }}</th>
                <th style="width: 10%;">{{ trans('admin/hardware/table.checkout_date') }}</th>
            </tr>
            @php
            $lcounter = 1;
            @endphp

            @foreach ($licenses as $license)

            <tr>
                <td>{{ $lcounter }}</td>
                <td>{{ $license->name }}</td>
                <td>
                    @can('viewKeys', $license)
                    {{ $license->serial }}
                    @else
                    <i class="fa-lock" aria-hidden="true"></i> {{ str_repeat('x', 15) }}
                    @endcan
                </td>
                <td>{{ $license->pivot->created_at }}</td>
            </tr>
            @php
            $lcounter++
            @endphp
            @endforeach
        </tbody>
    </table>
    @endif


    @if ($accessories->count() > 0)
    <br><br>
    <table class="inventory">
        <thead>
            <tr>
                <th colspan="4">{{ trans('general.accessories') }}</th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th style="width: 40%;">{{ trans('general.name') }}</th>
                <th style="width: 50%;">{{ trans('general.category') }}</th>
                <th style="width: 10%;">{{ trans('admin/hardware/table.checkout_date') }}</th>
            </tr>
        </thead>
        @php
        $acounter = 1;
        @endphp

        @foreach ($accessories as $accessory)
        @if ($accessory)
        <tr>
            <td>{{ $acounter }}</td>
            <td>{{ ($accessory->manufacturer) ? $accessory->manufacturer->name : '' }} {{ $accessory->name }} {{
                $accessory->model_number }}</td>
            <td>{{ $accessory->category->name }}</td>
            <td>{{ $accessory->pivot->created_at }}</td>
        </tr>
        @php
        $acounter++
        @endphp
        @endif
        @endforeach
    </table>
    @endif

    @if ($consumables->count() > 0)
    <br><br>
    <table class="inventory">
        <thead>
            <tr>
                <th colspan="4">{{ trans('general.consumables') }}</th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th style="width: 40%;">{{ trans('general.name') }}</th>
                <th style="width: 50%;">{{ trans('general.category') }}</th>
                <th style="width: 10%;">{{ trans('admin/hardware/table.checkout_date') }}</th>
            </tr>
        </thead>
        @php
        $ccounter = 1;
        @endphp

        @foreach ($consumables as $consumable)
        @if ($consumable)
        <tr>
            <td>{{ $ccounter }}</td>


            <td>
                @if ($consumable->deleted_at!='')
            <td>{{ ($consumable->manufacturer) ? $consumable->manufacturer->name : '' }} {{ $consumable->name }} {{
                $consumable->model_number }}</td>
            @else
            {{ ($consumable->manufacturer) ? $consumable->manufacturer->name : '' }} {{ $consumable->name }} {{
            $consumable->model_number }}
            @endif
            </td>
            <td>{{ ($consumable->category) ? $consumable->category->name : ' invalid/deleted category' }} </td>
            <td>{{ $consumable->pivot->created_at }}</td>
        </tr>
        @php
        $ccounter++
        @endphp
        @endif
        @endforeach
    </table>
    @endif
    <br>
    <br>
    <table>
        <thead>
            <tr>
                <th class="respons_header"> {{ trans('general.purpose') }} </th>
                <th class="respons_txt"> {{ trans('general.purpose_txt')}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="respons_header" scope="col">
                    {{ trans('general.respons') }}
                </th>
                <th scope="col" class="respons_txt_2"> {{ trans('general.responsibility') }}</th>
            </tr>
        </tbody>
    </table>
    <br>
    <p class="respons_txt_2">{{ trans('general.responsibility_2') }}</p>
    {{ trans('admin/users/general.all_assigned_list_generation')}} {{ Helper::getFormattedDateObject(now(), 'datetime',
    false) }}
    <br>
    <br>

    <p>{{ trans('general.date') }}:</p>
    <div class="signature">
        <p>{{ trans('general.signed_off_by') }}</p>
        <p>{{ trans('general.signed_off_recipient') }}</p>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>
    <script>
        function printPageArea(areaID){
            var printContent = document.getElementById(areaID).innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
        window.onafterprint = function() {
        window.location.reload(true);
    };
    </script>
</body>

</html>