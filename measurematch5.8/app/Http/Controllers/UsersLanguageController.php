<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Authenticatable,
    CanResetPassword;
use App\Http\Requests;
use Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Redirect;
use App\Model\User;
use Session;
use Validator;
use DB;
use App\Model\Language;
use App\Model\UserType;
use App\Model\UserProfile;
use App\Model\UsersLanguage;

class UsersLanguageController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function savelanguage(Request $request) {

        $user_id = Auth::user()->id;
        $language_form_input = $request->all();

        $rules = array(
            'userlanguage' => 'required',
        );
        $validator = Validator::make($language_form_input, $rules);
        if ($validator->fails()) {
            return back();            
        } else {
            $language_name = trim($language_form_input['userlanguage']);
            $language = new Language;
            $user_language_data = new UsersLanguage;
            $check_language = Language::getLanguageWithName(trim($language_name));

            if (empty($check_language)) {

                $language->name = $language_name;
                $language->save();

                $addedlangid = $language->id;
                $user_language_data->user_id = $user_id;
                $user_language_data->language_id = $addedlangid;
                $user_language_data->language_proficiency = trim($language_form_input['languageproficiency']);
                $user_language_data->save();
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
            } else {
                $existed_language_id = $check_language['0']['id'];
                $check_exist_language = UsersLanguage::getUsersLanguagewithLanguageId($existed_language_id, $user_id);
                if (empty($check_exist_language)) {
                    $user_language_data->user_id = $user_id;
                    $user_language_data->language_id = $existed_language_id;
                    $user_language_data->language_proficiency = trim($language_form_input['languageproficiency']);
                    $user_language_data->save();
                    return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');                    
                } else {
                    return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
                }
            }
        }
    }
    public function editlanguage(Request $request) {
        $id = Auth::user()->id;
        $edit_language_data = $request->all();
        $rules = array(
            'edituserlanguage' => 'required',
        );
        $validator = Validator::make($edit_language_data, $rules);
        if ($validator->fails()) {
            $error = $validator->errors()->toArray();
            if ($error['edituserlanguage']['0']) {
                echo 'User Language field is required!!';
                die;
            }
        } else {
            
            $check_language =Language::getLanguageWithName(trim($edit_language_data['edituserlanguage']));
            $user_id = $id;
            if (empty($check_language)) {
                
                $check_exist_language = UsersLanguage::getUsersLanguagewithLanguageId(trim($edit_language_data['editlanguageid']), $id);
                $user_language_data = UsersLanguage::find($check_exist_language[0]['id']);
                $language = new Language;
                $language->name = trim($edit_language_data['edituserlanguage']);
                $language->save();
                $user_language_data->user_id = $id;
                $user_language_data->language_id = $language->id;
                $user_language_data->language_proficiency = trim($edit_language_data['editlanguageproficiency']);
                $user_language_data->save();
                return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
            } else {
                $existing_language_id = $check_language['0']['id'];
                 $check_exist_language = UsersLanguage::getUsersLanguagewithLanguageId($existing_language_id, $id);
                $check_existing_language = UsersLanguage::getUsersLanguagewithLanguageId(trim($edit_language_data['editlanguageid']), $id);
               
                if (empty($check_exist_language)) {
                    $user_languaged = UsersLanguage::find($check_existing_language[0]['id']);
                    $user_languaged->user_id = $id;
                    $user_languaged->language_id = $existing_language_id;
                    $user_languaged->language_proficiency = trim($edit_language_data['editlanguageproficiency']);
                    $user_languaged->save();
                    return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
                } else {
                    $check_exist_language = UsersLanguage::getUsersLanguagewithLanguageId(trim($edit_language_data['editlanguageid']), $id);

                    $check_language_proficiency = UsersLanguage::getUsersLanguagewithLanguageProficiency($existing_language_id, trim($edit_language_data['editlanguageproficiency']), $id);
                    if (empty($check_language_proficiency)) {
                        $user_language_proficiency = UsersLanguage::find($check_exist_language[0]['id']);
                        $user_language_proficiency->user_id = $id;
                        $user_language_proficiency->language_id = trim($edit_language_data['editlanguageid']);
                        $user_language_proficiency->language_proficiency = trim($edit_language_data['editlanguageproficiency']);
                        $user_language_proficiency->save();
                        return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
                        } else {             
                        return Redirect::To('expert/profile-summary')->with('warning', 'Account updated.');
                    }
                }
            }
        }
    }

}
