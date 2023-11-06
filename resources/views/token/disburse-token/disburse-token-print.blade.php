
@extends('crudbooster::admin_template')
@push('head')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<style type="text/css">   
    #border-table {
    padding: 15px;
    }
   

    
</style>
@endpush
@section('content')
<!-- link -->
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif
  <div class='panel panel-default' style="width:100% !important; ">
    <div class='panel-heading'>  
        Print Form
    </div>
    <form method='' id="myform" action=""> 
        <div class='panel-body'>    
            <div id="printableArea"> 
                    <table width="100%" style="font-size: 13px;">
                        
                        <tr>
                            <td colspan="4">
                                <h4 align="center" ><strong>DR Form (GIS)</strong></h4> 
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><hr/></td>
                          
                        </tr>
                        <tr>
                            <td width="20%"><label><strong>Disburse#: <strong></label></td>
                            <td width="40%"><p>{{$disburseToken->disburse_number}}</p></td>

                            <td width="20%"><label><strong>Created date:<strong></label></td>
                            <td width="40%"><p>{{$disburseToken->created_at}}</p></td>
                        </tr>
                        
                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>

                        <tr style="margin-bottom:50px">
                            <td colspan="4">
                                <table border="1" width="100%" style="text-align:center;border-collapse: collapse; table-layout: fixed; font-size: 13px;" >
                                    
                                    <thead>
                                        <tr id="border-table">
                                            <th id="border-table" style="text-align:center" width="10%;">Disburse#</th>
                                            <th id="border-table" style="text-align:center" width="5%">Released Qty</th>
                                            <th id="border-table" style="text-align:center" width="10%">Description</th>
                                            <th id="border-table" style="text-align:center" width="10%">From location</th>          
                                            <th id="border-table" style="text-align:center" width="15%">To location</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="border-table">
                                            <td id="border-table">
                                                {{$disburseToken->disburse_number}}
                                            </td>
                                            <td id="border-table">
                                                {{$disburseToken->released_qty}}
                                            </td>
                                            <td id="border-table">
                                               TOKEN
                                            </td>
                                            <td id="border-table">
                                                {{$disburseToken->from_location}}
                                            </td>
                                            <td id="border-table">
                                                {{$disburseToken->to_location}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> 
                            </td>
                        </tr>

                      
       
                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>

                        <tr>
                            <td width="20%">
                                <label><strong>Processed By:<strong></label>
                            </td>
                            <td width="40%"><p>{{$disburseToken->requested_name}}</p></td>
                            <td width="20%"><label><strong>Processed Date:<strong></label></td>
                            <td><p>{{$disburseToken->created_at}}</p></td>
                        </tr>

                        {{-- <tr>
                            <td colspan="4"><hr/></td>
                        </tr> --}}

                        {{-- <tr>
                            <td colspan="4">
                                <h5 align="center" ><strong>UNDERTAKING</strong></h5> 
                            </td>
                        </tr> --}}

                        {{-- <tr>
                            <td colspan="4">
                                <p>
                                    I, <strong>{{$disburseToken->requested_name}}</strong> will ensure that this form is signed by the receiver and received in system before receiving of company assets
                                </p>
                            </td>
                        </tr> --}}

                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>

                        <tr>
                            <td width="20%">
                                <label><strong>Received By:<strong></label>
                            </td>

                            <td width="40%">
                                <p></p>
                            </td>

                            <td width="20%">
                                <label><strong>Received Date:<strong></label>
                            </td>

                            <td>
                                <p></p>
                            </td>
                        </tr>

                    </table> 

                    <hr><br><br><br><br><br>

                    <table class="table" width="100%" style="font-size: 13px;">
                    
                        <tr>
                            <td colspan="4">
                                <h4 align="center" ><strong>DR Form (GIS)</strong></h4> 
                            </td>
                        </tr>
    
                        <tr>
                            <td colspan="4"><hr/></td>
                          
                        </tr>
                        <tr>
                            <td width="20%"><label><strong>Disburse#: <strong></label></td>
                            <td width="40%"><p>{{$disburseToken->disburse_number}}</p></td>
    
                            <td width="20%"><label><strong>Created date:<strong></label></td>
                            <td width="40%"><p>{{$disburseToken->created_at}}</p></td>
                        </tr>
                        
                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>
    
                        <tr style="margin-bottom:50px">
                            <td colspan="4">
                                <table border="1" style="text-align:center;border-collapse: collapse; table-layout: fixed; font-size: 13px;" width="100%">
                                    
                                    <thead>
                                        <tr id="border-table">
                                            <th id="border-table" style="text-align:center" width="10%;">Disburse#</th>
                                            <th id="border-table" style="text-align:center" width="5%">Released Qty</th>
                                            <th id="border-table" style="text-align:center" width="10%">Description</th>
                                            <th id="border-table" style="text-align:center" width="10%">From location</th>          
                                            <th id="border-table" style="text-align:center" width="15%">To location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="border-table">
                                            <td id="border-table">
                                                {{$disburseToken->disburse_number}}
                                            </td>
                                            <td id="border-table">
                                                {{$disburseToken->released_qty}}
                                            </td>
                                            <td id="border-table">
                                               TOKEN
                                            </td>
                                            <td id="border-table">
                                                {{$disburseToken->from_location}}
                                            </td>
                                            <td id="border-table">
                                                {{$disburseToken->to_location}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> 
                            </td>
                        </tr>
    
                      
       
                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>
    
                        <tr>
                            <td width="20%">
                                <label><strong>Processed By:<strong></label>
                            </td>
                            <td width="40%"><p>{{$disburseToken->requested_name}}</p></td>
                            <td width="20%"><label><strong>Processed Date:<strong></label></td>
                            <td><p>{{$disburseToken->created_at}}</p></td>
                        </tr>
    
                        {{-- <tr>
                            <td colspan="4"><hr/></td>
                        </tr> --}}
    
                        {{-- <tr>
                            <td colspan="4">
                                <h5 align="center" ><strong>UNDERTAKING</strong></h5> 
                            </td>
                        </tr> --}}
    
                        {{-- <tr>
                            <td colspan="4">
                                <p>
                                    I, <strong>{{$disburseToken->requested_name}}</strong> will ensure that this form is signed by the receiver and received in system before receiving of company assets
                                </p>
                            </td>
                        </tr> --}}
    
                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>
    
                        <tr>
                            <td width="20%">
                                <label><strong>Received By:<strong></label>
                            </td>
    
                            <td width="40%">
                                <p></p>
                            </td>
    
                            <td width="20%">
                                <label><strong>Received Date:<strong></label>
                            </td>
    
                            <td>
                                <p></p>
                            </td>
                        </tr>
    
                    </table> 
            </div>

        </div>
        <div class='panel-footer'>
            <input type="hidden" id="header_id" name="header_id" value="{{ $disburseToken->dt_id }}">
            
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>

            <button class="btn btn-primary pull-right" type="submit" id="printARF" onclick="printDivision('printableArea')"> <i class="fa fa-print" ></i> Print</button>
       
        </div>
    </form> 
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

            var data = $('#myform').serialize();
                $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/store_rr_token/forPrintUpdate') }}',
                        data: data,
                        success: function( response ){
                            const data = JSON.parse(response);
                            if(data.status === 'success'){
                                //window.location.replace(document.referrer);
                                window.location.replace(data.redirect_url);
                            }else{
                                Swal.fire({
                                    type: 'error',
                                    title: data.message,
                                    icon: 'error',
                                    confirmButtonColor: '#3c8dbc',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.replace(data.redirect_url);
                                    }
                                });
                               
                            }      
  
                        },
                        error: function( e ) {
                            console.log(e);
                        }
                  });
                return false;
        });

    </script>
@endpush