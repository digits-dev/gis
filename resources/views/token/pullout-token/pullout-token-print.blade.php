
@extends('crudbooster::admin_template')
@push('head')
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
  <div class='panel panel-default'>
    <div class='panel-heading'>  
        Print Pullout Token Form
    </div>
    <form method='' id="myform" action=""> 
        <div class='panel-body'>    
            <div id="printableArea"> 
                    <table width="100%" style="font-size: 13px;">
                        
                        <tr>
                            <td colspan="4">
                                <h4 align="center" ><strong>Pullout Token Form (GIS)</strong></h4> 
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><hr/></td>
                          
                        </tr>
                        <tr>
                            <td width="20%"><label><strong>Reference Number: <strong></label></td>
                            <td width="40%"><p>{{$pulloutToken->reference_number}}</p></td>

                            <td width="20%"><label><strong>Created date:<strong></label></td>
                            <td width="40%"><p>{{$pulloutToken->created_at}}</p></td>
                        </tr>
                        
                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>

                        <tr style="margin-bottom:50px">
                            <td colspan="4">
                                <table border="1" width="100%" style="text-align:center; border-collapse: collapse; font-size: 15px; height:20px">
                                    
                                    <thead>
                                        <tr id="border-table">
                                            <th id="border-table" style="text-align:center" width="10%;">Reference Number</th>
                                            <th id="border-table" style="text-align:center" width="8%">Pullout Qty</th>
                                            <th id="border-table" style="text-align:center" width="10%">Description</th>
                                            <th id="border-table" style="text-align:center" width="30%">From location</th>          
                                            <th id="border-table" style="text-align:center" width="10%">To location</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="border-table">
                                            <td id="border-table">
                                                {{$pulloutToken->reference_number}}
                                            </td>
                                            <td id="border-table">
                                                {{$pulloutToken->qty}}
                                            </td>
                                            <td id="border-table">
                                               TOKEN
                                            </td>
                                            <td id="border-table">
                                                {{$pulloutToken->from_location}}
                                            </td>
                                            <td id="border-table">
                                                {{$pulloutToken->to_location}}
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
                            <td width="40%"><p>{{$pulloutToken->requested_name}}</p></td>
                            <td width="20%"><label><strong>Processed Date:<strong></label></td>
                            <td><p>{{$pulloutToken->created_at}}</p></td>
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
                                    I, <strong>{{$pulloutToken->requested_name}}</strong> will ensure that this form is signed by the receiver and received in system before receiving of company assets
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

                    <table width="100%" style="font-size: 13px;">
                    
                        <tr>
                            <td colspan="4">
                                <h4 align="center" ><strong>Pullout Token Form (GIS)</strong></h4> 
                            </td>
                        </tr>
    
                        <tr>
                            <td colspan="4"><hr/></td>
                          
                        </tr>
                        <tr>
                            <td width="20%"><label><strong>Reference Number: <strong></label></td>
                            <td width="40%"><p>{{$pulloutToken->reference_number}}</p></td>
    
                            <td width="20%"><label><strong>Created date:<strong></label></td>
                            <td width="40%"><p>{{$pulloutToken->created_at}}</p></td>
                        </tr>
                        
                        <tr>
                            <td colspan="4"><hr/></td>
                        </tr>
    
                        <tr style="margin-bottom:50px">
                            <td colspan="4">
                                <table border="1" width="100%" style="text-align:center; border-collapse: collapse; font-size: 15px; height:20px">
                                    
                                    <thead>
                                        <tr id="border-table">
                                            <th id="border-table" style="text-align:center" width="10%;">Reference Number</th>
                                            <th id="border-table" style="text-align:center" width="8%">Pullout Qty</th>
                                            <th id="border-table" style="text-align:center" width="10%">Description</th>
                                            <th id="border-table" style="text-align:center" width="30%">From location</th>          
                                            <th id="border-table" style="text-align:center" width="10%">To location</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="border-table">
                                            <td id="border-table">
                                                {{$pulloutToken->reference_number}}
                                            </td>
                                            <td id="border-table">
                                                {{$pulloutToken->qty}}
                                            </td>
                                            <td id="border-table">
                                               TOKEN
                                            </td>
                                            <td id="border-table">
                                                {{$pulloutToken->from_location}}
                                            </td>
                                            <td id="border-table">
                                                {{$pulloutToken->to_location}}
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
                            <td width="40%"><p>{{$pulloutToken->requested_name}}</p></td>
                            <td width="20%"><label><strong>Processed Date:<strong></label></td>
                            <td><p>{{$pulloutToken->created_at}}</p></td>
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
                                    I, <strong>{{$pulloutToken->requested_name}}</strong> will ensure that this form is signed by the receiver and received in system before receiving of company assets
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
            <input type="hidden" id="header_id" name="header_id" value="{{ $pulloutToken->pt_id }}">
            
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>

            <button class="btn btn-primary pull-right" type="submit" id="printARF" onclick="printDivision('printableArea')"> <i class="fa fa-print" ></i> Print</button>
       
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
                        url: '{{ url('admin/pullout_tokens/forPrintPulloutUpdate') }}',
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