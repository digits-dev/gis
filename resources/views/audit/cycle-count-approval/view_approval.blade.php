@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
           #table_dashboards th, td {
                border: 1px solid rgba(000, 0, 0, .4);
                padding: 8px;
            }
            table {
            border-collapse: collapse;
            }
        </style>
    @endpush
@section('content')

<div class='panel panel-default'>
    <div class='panel-body'>
        <div class="row">
            <div class="col-md-12">
                <button type="button" id="btn-export" class="btn btn-primary btn-sm btn-export" style="margin-bottom:10px"><i class="fa fa-download"></i>
                    <span>Export Data</span>
                </button>
                <form action="#" method="POST" id="cycleCountForApproval" enctype="multipart/form-data">
                    <table id="table_dashboards"> 
                        <thead style="background-color: #F5F5F5; color:#3c8dbc;">
                            <tr>
                                <th style="text-align: center" colspan="4">STOCK ROOM</th>
                                <th></th>
                                <th style="text-align: center" colspan="4">MACHINES</th>
                            </tr>
                            <tr class="active">
                                <th style="width:8%; text-align: center">Jan#</th>   
                                <th style="width:8%; text-align: center">Ref#</th> 
                                <th style="width:8%; text-align: center">System Qty</th>  
                                <th style="width:8%; text-align: center">Actual Qty</th>       
                                <th style="width:1%"></th>
                                <th style="width:8%; text-align: center">Ref#</th> 
                                <th style="width:8%; text-align: center">Machine</th> 
                                <th style="width:8%; text-align: center">System Qty</th>  
                                <th style="width:8%; text-align: center">Actual Qty</th>          
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forApproval as $res)
                                <tr style="text-align: center" >
                                    <td data-merge="{{$res->st_digits_code}}">{{$res->st_digits_code}}</td>
                                    <td data-merge="{{$res->st_digits_code}}">{{$res->st_ref_number}}</td>
                                    <td data-merge="{{$res->st_digits_code}}">{{$res->st_system_qty}}</td>
                                    <td data-merge="{{$res->st_digits_code}}">{{$res->st_actual_qty}}</td>
                                    <td></td>
                                    <td>{{$res->floor_ref}}</td>
                                    <td>{{$res->floor_machine}}</td>
                                    <td>{{$res->floor_system_qty}}</td>
                                    <td>{{$res->floor_actual_qty}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class='panel-footer'>
        <a href="#" id="cancel-form" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
        <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-thumbs-up"></i>
            {{ trans('message.form.approved') }}</button>
    </div>
</div>

@endsection

@push('bottom')
<script src=
"https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js" >
    </script>
    <script src=
"https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" >
    </script>
        <script src=
"https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js" >
    </script>

    <script type="text/javascript">
        var table;
        $(document).ready(function () {
            table = $("#table_dashboards").DataTable({
                bPaginate: false,
                bLengthChange: false,
                bFilter: false,
                bInfo: true,
                bAutoWidth: false,
                buttons: [
                    {
                        extend: "excel",
                        title: "Cycle-count-for-approval",
                    },
                ],
           })
           $("#btn-export").on("click", function () {
                table.button(".buttons-excel").trigger();
            });

            formatTableRowSpan();
        });

        function formatTableRowSpan(){
            const table = document.querySelector('table');
            let headerCell = null;
            let header2Cell = null;
            let header3Cell = null;
            let header4Cell = null;
            for (let row of table.rows) {
                const firstCell = row.cells[0];
                const secondCell = row.cells[1];
                const thirdCell = row.cells[2];
                const fourthCell = row.cells[3];
                if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
                    headerCell = firstCell;
                    header2Cell = secondCell;
                    header3Cell = thirdCell;
                    header4Cell = fourthCell;
                } else {
                    headerCell.rowSpan++;
                    firstCell.remove();
                    header2Cell.rowSpan++;
                    secondCell.remove();
                    header3Cell.rowSpan++;
                    thirdCell.remove();
                    header4Cell.rowSpan++;
                    fourthCell.remove();
                }

            }
        }
    </script>
@endpush