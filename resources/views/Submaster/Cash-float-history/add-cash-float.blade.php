<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
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

    .swal2-popup {
        font-size: 1.5rem !important;
    }

    .swal2-popup .swal2-title {
        font-size: 2.5rem !important;
    }

    .swal2-popup .swal2-content {
        font-size: 1.8rem !important;
    }

    .swal2-popup {
        margin: auto !important;
        position: absolute !important;
        left: 0 !important;
        right: 0 !important;
        top: 10 !important;
        transform: translateY(-50%) !important;
    }

    .control-label{
        margin-right: 10px;
    }

    #cash_float_lines_add_btn, #cash_float_lines_remove_btn{
        /* border: none; */
        padding: 7px 20px;
    }

    .cfl_original{
        background-color: #eeeeee;
        padding-bottom: 5px;
        margin: 0;
        box-shadow: rgba(67, 71, 85, 0.27) 0px 0px 0.25em, rgba(90, 125, 188, 0.05) 0px 0.25em 1em;
    }

    .cfl_clone{
        background-color: #eeeeee;
        box-shadow: rgba(67, 71, 85, 0.27) 0px 0px 0.25em, rgba(90, 125, 188, 0.05) 0px 0.25em 1em;
        margin: 26px 0 0 0;
        padding-bottom: 5px;
        position: relative;
    }

    #remove_btn{
        position: absolute;
        right: -10px;
        top: -13px;
        font-size: 25px;
        color: #DD4B39;
        background-color: #ececec;
        /* padding: 0 7px; */
        height: 30px;
        width: 30px;
        border-radius: 50%;
        box-shadow: rgba(0, 0, 0, 0.18) 0px 2px 4px;
        cursor: pointer;
        display: grid;
        place-content: center;
        z-index: 1;
    }


</style>
@endpush
@section('content')
    <p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
    <div class='panel panel-default'>
        <div class='panel-heading' style="background-color:#dd4b39; color:#fff">Add Cash Float</div>
        <div class="hide row cfl_clone">
            <i id="remove_btn" class="fa fa-close"></i>
            <div class="col-md-3 cfl-row">
                <label class="control-label" for="">Mode Of Payment</label>
                <select class="form-control select2-s1" name="mode_of_payment[]">
                    @foreach ($mode_of_payments as $mode_of_payment)
                        <option value="{{ $mode_of_payment->id }}">{{ $mode_of_payment->payment_description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="control-label" for="">Float Entry</label>
                <select class="form-control select2-s1" name="float_entry[]">
                    @foreach ($float_entries as $float_entry)
                        <option value="{{ $float_entry->id }}">{{ $float_entry->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="control-label" for="">Qty</label>
                <input class="form-control" type="text" name="qty[]">
            </div>
            <div class="col-md-3">
                <label class="control-label" for="">Value</label>
                <input class="form-control" type="text" name=value[]>
            </div>
        </div>
        <form method='post' action='{{CRUDBooster::mainpath('add-save')}}'>
        @csrf
        <div class='panel-body'>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="">Location</label>
                        <select class="form-control select2-s" id="test" name="location_id">
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="">Float Type</label>
                        <select class="form-control select2-s" id="test" name="float_id">
                            @foreach ($float_types as $float_type)
                                <option value="{{ $float_type->id }}">{{ $float_type->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>

                <div class="row cfl_original">
                    <div class="col-md-3 cfl-row">
                        <label class="control-label" for="">Mode Of Payment</label>
                        <select class="form-control select2-s" name="mode_of_payment[]">
                            @foreach ($mode_of_payments as $mode_of_payment)
                                <option value="{{ $mode_of_payment->id }}">{{ $mode_of_payment->payment_description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="">Float Entry</label>
                        <select class="form-control select2-s" name="float_entry[]">
                            @foreach ($float_entries as $float_entry)
                                <option value="{{ $float_entry->id }}">{{ $float_entry->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="">Qty</label>
                        <input class="form-control" type="text" name="qty[]">
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="">Value</label>
                        <input class="form-control" type="text" name=value[]>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" id="cash_float_lines_add_btn">Add cash float line</button>
                    </div>
                </div>

            </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <input type='button' class='btn btn-primary' id="submit" value='Submit'/>
                <input type='submit' class='hide btn btn-primary' id="submit_orig" value='Submit'/>
            </div>
        </form>
    </div>
@endsection
@push('bottom')
    <script>

        $('.select2-s').select2({
            "width":"100%"
        });

        // Initialize a variable to keep track of the last inserted element
        let lastInserted = null;

        $('#cash_float_lines_add_btn').on('click', function () {
            const clone = $('.cfl_clone').eq(0).clone(true);
            clone.removeClass('hide');
            clone.find('.select2-s1').select2({
                "width": "100%"
            });

            // Check if there is a last inserted element
            if (lastInserted) {
                // If a last inserted element exists, insert the clone after it
                clone.insertAfter(lastInserted);
            } else {
                // If no last inserted element, insert the clone after .cfl_original
                clone.insertAfter('.cfl_original');
            }

            lastInserted = clone;
        });

        $('#remove_btn').on('click',function(){
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Store a reference to the parent of the clicked remove button
                    const parentClone = $(this).parents('.cfl_clone');

                    // Fade out and remove the parent clone
                    parentClone.fadeOut(500, function () {
                        parentClone.remove();

                        // Reset lastInserted to null after removal
                        lastInserted = null;
                    });
                    
                    Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                    )
                }
            })
        })

        $('#submit').on('click', function(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#submit_orig').trigger('click');
                    Swal.fire(
                    'Saved!',
                    'Your file has been saved successfully.',
                    'success'
                    )
                }
            })
        })

        $(document).ready(function(){

            if("{{ Request::Segment(3) }}" == 'detail'){
                $('input, select').prop('disabled', true);
                $('#cash_float_lines_add_btn').hide();
            }
        })

    </script>
@endpush