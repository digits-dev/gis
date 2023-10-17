@extends('crudbooster::admin_template')
    @push('head')
        <style type="text/css">   
            .select2-selection__choice{
                    font-size:14px !important;
                    color:black !important;
            }
            .select2-selection__rendered {
                line-height: 31px !important;
            }
            .select2-container .select2-selection--single {
                height: 35px !important;
            }
            .select2-selection__arrow {
                height: 34px !important;
            }

        </style>
    @endpush
@section('content')
@if(g('return_url'))
	<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
	<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif

<div class='panel panel-default'>
    <div class='panel-heading'>
        Add Machine Form
    </div>

    <form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="ApprovalMatrix" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
       
        <div class='panel-body'>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><span style="color:red">*</span> Location</label>
                        <select  id="location" name="location" class="form-select select2" style="width:100%;">
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="require control-label"> No of tokens:</label>
                        <input type="text" class="form-control finput" style="" placeholder="No of tokens" name="no_of_tokens" id="no_of_tokens">
                    </div>
                </div>
            </div>
        </div>

        <div class='panel-footer'>

            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>

            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.save') }}</button>

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

        $('#location').select2()
    </script>
@endpush