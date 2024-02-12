@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
           #table_dashboards th, td {
                border: 1px solid rgba(000, 0, 0, .4);
                padding: 8px;
            }
            table.dataTable td.dataTables_empty {
                text-align: center;    
            }
        </style>
    @endpush
@section('content')

<div class='panel panel-default'>
    <div class='panel-body'>
        <div class="row">
            <div class="col-md-12">
                <table id="table_dashboards"> 
                    <thead style="background-color: #F5F5F5; color:#3c8dbc">
                        <tr class="active">
                            <th style="width:2%">Action</th>
                            <th style="width:6%">Status</th>
                            <th style="width:8%">Location</th>            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $val)
                        <tr>
                            @if(in_array(CRUDBooster::myPrivilegeId(),[1,14]))
                                <td style="text-align:center">   
                                    <a data-toggle="tooltip" data-placement="right" title="Approve" class='btn btn-xs btn-success' href='{{CRUDBooster::mainpath("view-approval/".$val->location_id)."?return_url=".urlencode(Request::fullUrl())}}'><i class='fa fa-pencil'></i></a>                                         
                                </td>  
                            @endif
                            <td style="text-align:center">
                                <label class="label label-success" style="align:center; font-size:10px">{{$val->status}}</label>
                            </td>
                            <td>{{$val->location_name}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
       $(document).ready(function() {
                table = $("#table_dashboards").DataTable({
                    ordering:false,
                    pageLength:25,
                    language: {
                        searchPlaceholder: "Search"
                    },
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"],
                        ],
                    buttons: [
                        {
                            extend: "excel",
                            title: "Applicant",
                            exportOptions: {
                            columns: ":not(.not-export-column)",
                            columns: ":gt(0)",
                                modifier: {
                                page: "current",
                            }
                            },
                        },
                    ],
                });
       });
    </script>
@endpush