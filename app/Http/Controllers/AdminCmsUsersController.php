<?php namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDbooster;
use crocodicstudio\crudbooster\controllers\CBController;

class AdminCmsUsersController extends \crocodicstudio\crudbooster\controllers\CBController {


	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'cms_users';
		$this->primary_key         = 'id';
		$this->title_field         = "name";
		$this->button_action_style = 'button_icon';	
		$this->button_import 	   = FALSE;	
		$this->button_export 	   = FALSE;	
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Name","name"=>"name");
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);		
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array(); 		
		$this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|alpha_spaces|min:3','width'=>'col-sm-6');
		$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(),'width'=>'col-sm-6');		
		$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'required'=>true,'validation'=>'required|image|max:1000','resize_width'=>90,'resize_height'=>90,'width'=>'col-sm-6');											
		$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true,'width'=>'col-sm-6');						
		$this->form[] = array("label"=>"Location","name"=>"location_id","type"=>"select2","datatable"=>"locations,location_name", 'datatable_where'=>"status = 'ACTIVE'",'width'=>'col-sm-6');
		// $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change");
		$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change",'width'=>'col-sm-6');
		$this->form[] = array("label"=>"Password Confirmation","name"=>"password_confirmation","type"=>"password","help"=>"Please leave empty if not change",'width'=>'col-sm-6');
		# END FORM DO NOT REMOVE THIS LINE
			
		
		$this->script_js = "
		$(document).ready(function() {
				$('form').submit(function(){
	
						$('.btn.btn-success').attr('disabled', true);
						return true; 
				});

				$('.js-example-basic-multiple').select2();

				let x = $(location).attr('pathname').split('/');
				let add_action = x.includes('add');
				let edit_action = x.includes('edit');
				

				if (add_action){
					$('#form-group-location_id').hide();
					$('#location_id').removeAttr('required');

					$('#id_cms_privileges').change(function() {
						if($(this).val() != 3){
							$('#form-group-location_id').hide();
							$('#location_id').removeAttr('required');
						}else{
							$('#form-group-location_id').show();
							$('#location_id').attr('required', 'required');
						}
					});

				}else if(edit_action){
					$('#form-group-location_id').hide();
					$('#location_id').removeAttr('required');

					$('#id_cms_privileges').change(function() {
						if($(this).val() != 3){
							$('#form-group-location_id').hide();
							$('#location_id').removeAttr('required');
						}else{
							$('#form-group-location_id').show();
							$('#location_id').attr('required', 'required');
						}
					});

					if($('#id_cms_privileges').val() != 3){
						$('#form-group-location_id').hide();
						$('#location_id').removeAttr('required');	
					}else{
						$('#form-group-location_id').show();
						$('#location_id').attr('required', 'required');
					}
				}

				$('input[name=\"submit\"]').click(function(){
					var strconfirm = confirm('Are you sure you want to save?');
					if (strconfirm == true) {
						return true;
					}else{
						return false;
						window.stop();
					}

				});

			});
			";	
	}

	
	public function getProfile() {			

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;			
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;	
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = cbLang("label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',CRUDBooster::myId());

        return $this->view('crudbooster::default.form',$data);
	}
	public function hook_before_edit(&$postdata,$id) { 
		unset($postdata['password_confirmation']);
	}
	public function hook_before_add(&$postdata) {      
	    unset($postdata['password_confirmation']);
	}
}
