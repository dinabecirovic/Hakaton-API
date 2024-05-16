<?php

namespace App\Http\Controllers\Api;

use App\Events\UserEmailUpdated;
use App\Events\UserProfileUpdated;
use App\Http\Resources\UserAvatarResource;
use App\Http\Resources\UserBasicResource;
use App\Http\Resources\UserResource;
use App\Rules\VatNumber;
use App\Rules\ZIPCode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Rules\PasswordRule;
use Illuminate\Validation\ValidationException;
use App\Country;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|Response
     */
    public function indexByUUID(Request $request){

        if(!$request->uuid){
            return response(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $uuids = is_array($request->uuid) ? $request->uuid : [$request->uuid];

        return UserBasicResource::collection(User::whereIn('uuid', $uuids)->get());

    }

    /**
     * @param Request $request
     * @return UserResource|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function show(Request $request){

        $user = auth()->user();

        return new UserResource($user);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request){

        $user = auth()->user();

        $email_changed = false;

        $this->validate($request,  [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required',
        ]);

        if($request->email != $user->email) {
            if( User::where('email', $request->email)->where('id', '!=', $user->id)->count() ){
                throw ValidationException::withMessages([
                    'email' =>  'Email already taken!'
                ]);
            }

            $email_changed = true;
            $user->email = $request->email;
            $user->generateNewEmailVerifyToken();
            $user->save();
            $user->resendVerificationEmail();
        }

        $user->fill($request->all());

        $user->save();

        event(new UserProfileUpdated($user));

        if($email_changed){
            event(new UserEmailUpdated($user));
        }

        return response()->json([
            'email_changed' => $email_changed,
            'user' => new UserResource($user)
        ]);

    }

    /**
     * 
     */
    public function updateBillingInfo(Request $request){

        $user = auth()->user();

        $rules = [];

        if($request->country){

            $country = Country::getByCode($request->country);
            
            if($country->id != $user->country_id){

                if(!$user->canChangeCountry())
                    return response(null, Response::HTTP_UNPROCESSABLE_ENTITY);

            }

        }
        else{
            $country = auth()->user()->country;
        }

        $rules['zip'] = [];
        $rules['zip'][] = new ZIPCode($country);

        if($user->zip)
            $rules['zip'][] = 'required';

        if($user->address)
            $rules['address'] = 'required';

        if($user->city)
            $rules['city'] = 'required';

        // If user is Legal Entity, we need to make sure that company name and VAT number are provided
        if( ($request->company_user || $request->company_name) && $user->isLegalEntity()){

            $rules['vat'] = 'required';
            $rules['company_name'] = 'required';

        }

        // If user is Legal entity, and from EU, we need to validate VAT number
        if(($request->company_name || $request->company_user) && $country->is_eu_vat){
            $rules['vat'][] = new VatNumber;
        }

        if($user->canChangeProfileType()){

            if(
                $user->company_name != $request->company_name ||
                $user->vat != $request->vat
            ){
                
                return response(null, Response::HTTP_UNPROCESSABLE_ENTITY);

            }
                

        }

        // Validate
        $this->validate($request, $rules, [
            'vat_number' => "Invalid VAT number"
        ]);

        $user->zip = $request->zip;
        $user->country_id = $country->id;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->company_name = $request->company_name;
        $user->vat = $request->vat;

        if($user->isDirty()){
            $user->save();
            event(new UserProfileUpdated($user));
        }
        
        $user->save();

        return new UserResource($user);

    }

    public function changePassword(Request $request){
        $user = auth()->user();

        $rules = [
            'current_password' => 'required',
            'new_password' => ['required', new PasswordRule($user->first_name, $user->last_name, $user->email)]
        ];

        $this->validate($request, $rules);

        if(!Hash::check($request->current_password, $user->password)){
            throw ValidationException::withMessages([
                'current_password' =>  'Current password is incorrect!'
            ]);
        }

        $user->password = Hash::make($request->new_password);

        $user->save();


        if($request->log_out_from_all_devices){
            //remove all devices, except current one
            $user->managableApiTokens()->where('token', '!=', $request->bearerToken())->delete();
        }


        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function delete(Request $request){

        $user = auth()->user();

        $user->delete();

        return response(null, Response::HTTP_OK);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function avatar(Request $request){

        $user = auth()->user();

        $this->validate($request, [
            'image' => 'image|mimes:jpg,jpeg,png|dimensions:max_width=1024,max_height=1024'
        ]);


        $user->removeExistingAvatar();

        if($request->image){
            $user->addAvatar($request->file('image'));
        }

        $user->touch();

        event(new UserProfileUpdated($user));

        return response()->json([
            'avatar' => new UserAvatarResource($user),
        ]);

    }

}
