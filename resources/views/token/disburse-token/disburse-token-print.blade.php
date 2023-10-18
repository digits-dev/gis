
@extends('crudbooster::admin_template')
@push('head')
<style type="text/css">   
</style>
@endpush
@section('content')
<!-- link -->
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
  <div class='panel panel-default'>
    <div class='panel-heading'>  
        Print Form
    </div>
    <form method='' id="myform" action=""> 
        <div class='panel-body'>    
            <div id="printableArea"> 
                    <table width="100%" style="font-size: 13px;">

                        <tr>
                            <td colspan="4">
                                <h4 align="center" ><strong>Pick List Report (GIS)</strong></h4> 
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><hr/></td>
                          
                        </tr>
                        <tr>
                            <td width="20%"><label><strong>Arf Number: <strong></label></td>
                            <td width="40%"><p>{{$disburseToken->disburse_number}}</p></td>
                        </tr>
                        
                        <tr>
                            <td width="20%"><label><strong>Requested By:<strong></label></td>
                            <td width="40%"><p>{{$disburseToken->requestedby}}</p></td>

                            <td width="10%"><label><strong>Requested Date:<strong></label></td>
                            <td><p>{{ date('Y-m-d', strtotime($disburseToken->created)) }}</p></td>

                        </tr>

                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>

                        <tr>
                            <td colspan="4">
                                <table border="1" width="100%" style="text-align:center;border-collapse: collapse; font-size: 13px;">
                                    
                                    <thead>
                                        <tr>
                                            <th style="text-align:center" height="10" width="10%">Disburse#</th>
                                            <th style="text-align:center" height="10" width="8%">Released Qty</th>
                                            <th style="text-align:center" height="10" width="30%">From location</th>          
                                            <th style="text-align:center" height="10" width="10%">To location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td height="10">
                                                {{$disburseToken->disburse_number}}
                                            </td>
                                            <td height="10">
                                                {{$disburseToken->released_qty}}
                                            </td>
                                            <td height="10">
                                                {{$disburseToken->from_location}}
                                            </td>
                                            <td height="10">
                                                {{$disburseToken->to_location}}
                                            </td>
                                        </tr>
                                    </tbody>

                                    <tr>

                                    </tr>

                                </table> 
                            </td>
                        </tr>

                    </table> 
            </div>
        </div>
        <div class='panel-footer'>
            <input type="hidden" value="{{$Header->requestid}}" name="requestid">
            
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>

            <button class="btn btn-primary pull-right" type="submit" id="printARF" onclick="printDivision('printableArea')"> <i class="fa fa-print" ></i> Print as PDF</button>
       
        </div>
    </form> 
  </div>
@endsection
@push('bottom')
    <script type="text/javascript">

        function printDivision(divName) {
         //alert('Please print 2 copies!');
            var generator = window.open(",'printableArea,");
            var layertext = document.getElementById(divName);
            generator.document.write(layertext.innerHTML.replace("Print Me"));
            generator.document.close();
            generator.print();
            generator.close();
        }        

        $("#printARF").on('click',function(){
        //var strconfirm = confirm("Are you sure you want to approve this pull-out request?");
            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/store_rr_token/forPrintUpdate') }}',
                        data: data,
                        success: function( response ){
                            console.log( response );              
                        
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                return false;
        });

    </script>
@endpush