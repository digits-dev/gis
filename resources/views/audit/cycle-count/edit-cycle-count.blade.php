@push('head')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <style type="text/css">

        #collected-token th,  td {
            border: 1px solid rgba(000, 0, 0, .5) !important;
        }

        @media (min-width:729px) {
            .panel-default {
                width: 40% !important;
                margin: auto !important;
            }
        }
    </style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
    @if (g('return_url'))
        <p class="noprint"><a title='Return' href='{{ g('return_url') }}'><i class='fa fa-chevron-circle-left '></i> &nbsp;
                {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a></p>
    @else
        <p class="noprint"><a title='Main Module' href='{{ CRUDBooster::mainpath() }}'><i
                    class='fa fa-chevron-circle-left '></i> &nbsp;
                {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a></p>
    @endif

    <div class='panel panel-default'>
        <div class='panel-heading' style="background-color:#3c8dbc; color:#fff">
            Edit Cycle Count (Capsule) Form
        </div>

        <div class='panel-body'>
            <div class="col-md-12">

                <div class="form-group">
                    <label class="control-label"> Reference Number</label>
                    <input type="text" class="form-control finput" value="{{ $detail_header->reference_number }}"
                        readonly>
                </div>

                <div class="form-group">
                    <label class="control-label"> Location</label>
                    <input type="text" class="form-control finput" value="{{ $detail_header->location_name }}" readonly>
                </div>

                <div class="form-group">
                    <label class="require control-label"> Sub Location</label>
                    <input type="text" class="form-control finput" value="{{ $detail_header->sub_location_name }}" readonly>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered" id="collected-token">
                        <thead>
                            <tr style="vertical-align: top;">
                                <th width="20%" class="text-center">Line Status</th>
                                <th width="20%" class="text-center">Machine</th>
                                <th width="20%" class="text-center">Item Code</th>
                                <th width="20%" class="text-center">Qty</th>
                                <th width="20%" class="text-center">Variance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail_body as $row)
                                <tr>
                                    <td style="text-align:center">
                                        {{ $row->ccl_status }}
                                    </td>
                                    <td style="text-align:center">
                                        {{ $row->serial_number }}
                                    </td>
                                    <td style="text-align:center">
                                        {{ $row->digits_code }}
                                    </td>
                                    <td style="text-align:center" class="qty">
                                        {{ $row->qty }}
                                    </td>
                                    <td style="text-align:center" class="variance">
                                        {{ $row->variance }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right;"><b>Total</b></td>
                                <td class="text-center"><span id="totalQty">0</span></td>
                                <td class="text-center"><span id="totalVariance">0</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if ($detail_header->received_by != null)
                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%">
                            <tbody id="footer">
                                <tr>
                                    <th class="control-label col-md-2">{{ trans('message.form-label.received_by') }}:</th>
                                    <td class="col-md-4">{{ $detail_header->receiver_name }} /
                                        {{ $detail_header->received_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>

        </div>

    </div>
@endsection
@push('bottom')
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
        window.onunload = function() {
            null;
        };
        setTimeout("preventBack()", 0);

        $(document).ready(function() {
            $('#totalQty').text(calculateTotalQuantity());
            $('#totalVariance').text(calculateVarianceQuantity());
        });


        function calculateTotalQuantity() {
            let totalQuantity = 0;
            $('.qty').each(function() {
                let qty = 0;
                if($(this).text().trim()) {
                    qty = parseInt($(this).text().replace(/,/g, ''));
                }

                totalQuantity += qty;
            });
            return totalQuantity;
        }

        function calculateVarianceQuantity() {
            let varianceQuantity = 0;
            $('.variance').each(function() {
                let qty = 0;
                if ($(this).text().trim()) {

                    qty = parseInt($(this).text().replace(/,/g, ''));
                }

                varianceQuantity += qty;
            });
            return varianceQuantity;
        }

    </script>
@endpush
