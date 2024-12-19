<?php namespace App\Http\Controllers;

use CRUDbooster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
		$this->button_delete 	   = FALSE;
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Name","name"=>"name");
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"Location","name"=>"location_id","join"=>"locations,location_name");	
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);	
		$this->col[] = array("label"=>"Status","name"=>"status");	
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array(); 		
		if(in_array(CRUDBooster::myPrivilegeId(),[1,9])){
			$this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|alpha_spaces|min:3','width'=>'col-sm-5');
			$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(),'width'=>'col-sm-5');		
			$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000','resize_width'=>90,'resize_height'=>90,'width'=>'col-sm-5');	
		}else{
			$this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|alpha_spaces|min:3','width'=>'col-sm-5','readonly'=>true);
			$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(),'width'=>'col-sm-5','readonly'=>true);		
			$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000','resize_width'=>90,'resize_height'=>90,'width'=>'col-sm-5','readonly'=>true);	
		}
		
		if(CRUDBooster::isSuperadmin()){
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true,'width'=>'col-sm-5');	
		}else{
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true,'width'=>'col-sm-5', 'datatable_where'=>"id not in (1)");						
		}		
		
		if(in_array(CRUDBooster::myPrivilegeId(),[1,9])){
			$this->form[] = array("label"=>"Location","name"=>"location_id","type"=>"select2","datatable"=>"locations,location_name", 'datatable_where'=>"status = 'ACTIVE'",'width'=>'col-sm-5');
		}else{
			$this->form[] = array("label"=>"Location","name"=>"location_id","type"=>"select2","datatable"=>"locations,location_name", 'datatable_where'=>"status = 'ACTIVE'",'width'=>'col-sm-5','disabled'=>true);
		}
		
		// $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change");
		if(CRUDBooster::getCurrentMethod() != 'getProfile'){
			$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change",'width'=>'col-sm-5');
			$this->form[] = array("label"=>"Password Confirmation","name"=>"password_confirmation","type"=>"password","help"=>"Please leave empty if not change",'width'=>'col-sm-5');
		}
		# END FORM DO NOT REMOVE THIS LINE

		if((CRUDBooster::isSuperadmin()) && (CRUDBooster::getCurrentMethod() == 'getEdit' || CRUDBooster::getCurrentMethod() == 'postEditSave')){
		    $this->form[] = array("label"=>"Status","name"=>"status","type"=>"select","dataenum"=>"ACTIVE;INACTIVE",'required'=>true, 'width'=>'col-sm-5');
		}



		$this->style_css = "
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

		";
		
		$this->script_js = "
		$(document).ready(function() {
				$('form').submit(function(){
	
						$('.btn.btn-success').attr('disabled', true);
						return true; 
				});

				$('#location_id, #id_cms_privileges').select2();

				let x = $(location).attr('pathname').split('/');
				let add_action = x.includes('add');
				let edit_action = x.includes('edit');
				

				if (add_action){
					$('#form-group-location_id').hide();
					$('#location_id').removeAttr('required');

					$('#id_cms_privileges').change(function() {
						if($(this).val() == 3 || $(this).val() == 5 || $(this).val() == 6 || $(this).val() == 7 || $(this).val() == 10 || $(this).val() == 11 || $(this).val() == 12){
							$('#form-group-location_id').show();
							$('#location_id').attr('required', 'required');
						}else{
							$('#form-group-location_id').hide();
							$('#location_id').removeAttr('required');
						}
					});

				}else if(edit_action){
					$('#form-group-location_id').hide();
					$('#location_id').removeAttr('required');

					$('#id_cms_privileges').change(function() {
						if($(this).val() == 3 || $(this).val() == 5 || $(this).val() == 6 || $(this).val() == 7 || $(this).val() == 10 || $(this).val() == 11 || $(this).val() == 12){
							$('#form-group-location_id').show();
							$('#location_id').attr('required', 'required');
						}else{
							$('#form-group-location_id').hide();
							$('#location_id').removeAttr('required');
						}
					});

					if($('#id_cms_privileges').val() == 3 || $('#id_cms_privileges').val() == 5 || $('#id_cms_privileges').val() == 6 || $('#id_cms_privileges').val() == 7 || $('#id_cms_privileges').val() == 10 || $('#id_cms_privileges').val() == 11 || $('#id_cms_privileges').val() == 12){
						$('#form-group-location_id').show();
						$('#location_id').attr('required', 'required');
					}else{
						$('#form-group-location_id').hide();
						$('#location_id').removeAttr('required');
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

	public function hook_query_index(&$query) {
        if(CRUDBooster::myPrivilegeId() == 9) {
			$query->where('cms_users.id_cms_privileges','!=','1');
        }else{
			$query->orderBy('cms_users.id', 'desc');
		} 
    }

	public function hook_before_add(&$postdata) {      
	    unset($postdata['password_confirmation']);
		$postdata['status'] = 'ACTIVE';
	}

	public function hook_before_edit(&$postdata,$id) { 
		unset($postdata['password_confirmation']);
	}

	public function changePasswordView()
	{
		return view('users.change-password');
	}

	//change password
	public function changePass(Request $request)
	{
		$user_id = $request->input('user_id');
		$currentPass = $request->input('current_password');
		$newPass = $request->input('password');
		$hashNewPass = bcrypt($newPass);

		// Get user info to validate current password
		$getUserInfo = DB::table('cms_users')
			->where('id', $user_id)
			->first();

		// Validate if current password matches
		if (!Hash::check($currentPass, $getUserInfo->password)) {
			return response()->json(['currentPassMatch' => false, 'message' => 'Current password did not match!']);
		}

		$passwordLogsHistory = DB::table('password_history')
			->where('user_id', $user_id)
			->pluck('password');

		$isPasswordUsed = false;

		foreach ($passwordLogsHistory as $storedHash) {
			if (Hash::check($newPass, $storedHash)) {
				$isPasswordUsed = true;
				break; // Exit the loop match found
			}
		}

		if ($isPasswordUsed) {
			return response()->json(['passwordExists' => true]);   
		}

		$affected = DB::table('cms_users')
			->where('id', $user_id)
			->update([
				'password' => $hashNewPass,
				'last_password_updated' => now(),
				'waive_count' => 0
			]);

		DB::table('password_history')->insert([
			'user_id' => $user_id,
			'password' => $hashNewPass,
			'created_at' => now(),
		]);

		if ($affected) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false], 404);
	}

	//waive change password
	public function waiveChangePass(Request $request)
	{
		$user_id = $request->input('user_id');
		$waive = $request->input('waive');

		$affected = DB::table('cms_users')
			->where('id', $user_id)
			->update([
				'waive_count' => $waive,
				'last_password_updated' => now()
			]);
		Session::put('check-user',false);
		if ($affected) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false], 404);
	}
	
}
