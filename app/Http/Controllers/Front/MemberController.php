<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Member\MemberAddressRequest;
use App\Http\Requests\Front\Member\MemberDetailRequest;
use App\Http\Requests\Front\Member\MemberPasswordRequest;
use App\Models\Front\Address;
use App\Models\Front\Member;
use App\Models\Front\Province;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Hash;

class MemberController extends Controller
{
    public function index()
    {
        $prev = explode("/", url()->previous());
        $curr = explode("/", url()->current());
        ($prev[3] != $curr[3]) ? session()->forget('nav-account') : "";

        $member = auth()->guard('members')->user();
        $addresses = Address::with(['provinces', 'cities'])->whereMemberId($member->id)->orderByDesc('is_default')->get();

        return view('ecommerce.profile.index', compact('member', 'addresses'));
    }

    public function updateDetail(MemberDetailRequest $request, $id)
    {
        $member               = Member::findOrFail($id);
        $params['first_name'] = $request->first_name;
        $params['last_name']  = $request->last_name;
        $params['phone']      = $request->phone;
        $params['email']      = $request->email;

        \DB::transaction(
            function() use($member, $params) {
                return $member->update($params);
            }
        );

        session()->forget('nav-account');
        session()->put('nav-account', 'account');
        Alert::success("Success", "Update account detail");

        return back();
    }

    public function updatePassword(MemberPasswordRequest $request, $id)
    {
        $member      = Member::findOrFail($id);
        $params['password'] = bcrypt($request->password);

        if(!Hash::check($request->password, $member->password))
        {
            return back()->withErrors(['current_password' => 'Current password is wrong, please try again']);
        }

        \DB::transaction(
            function() use($member, $params) {
                return $member->update($params);
            }
        );

        session()->forget('nav-account');
        session()->put('nav-account', 'account');
        Alert::success("Success", "Update password account");

        return back();
    }

    public function addAddress()
    {
        $provinces = Province::all();

        return view('ecommerce.profile.address.create', compact('provinces'));
    }

    public function storeAddress(MemberAddressRequest $request)
    {
        $address = Address::whereMemberId(auth()->guard('members')->user()->id)->count();
        $params['member_id'] = $request->member_id;
        $params['name'] = $request->name;
        $params['postcode'] = $request->postcode;
        $params['street'] = $request->street;
        $params['is_default'] = $address == 0 ? 1 : ($request->is_default == 1 ? $this->updateIsDefaultAddressToFalse($request->member_id) : 0);
        $params['province_id'] = $request->province_id;
        $params['city_id'] = $request->city_id;

        \DB::transaction(
            function() use($params) {
                return Address::create($params);
            }
        );

        session()->forget('nav-account');
        session()->put('nav-account', 'address');
        Alert::success("Success", "Added new address");

        return redirect()->route('ecommerce.profile.index');
    }

    public function editAddress($id)
    {
        $address = Address::whereId($id)->first();
        $provinces = Province::all();

        return view('ecommerce.profile.address.edit', compact('address', 'provinces'));
    }

    public function updateAddress(MemberAddressRequest $request, $id)
    {
        $address = Address::whereId($id)->first();
        $params['member_id'] = $request->member_id;
        $params['name'] = $request->name;
        $params['postcode'] = $request->postcode;
        $params['street'] = $request->street;
        $params['is_default'] = $request->is_default == 1 ? $this->updateIsDefaultAddressToFalse($request->member_id) : 0;
        $params['province_id'] = $request->province_id;
        $params['city_id'] = $request->city_id;

        \DB::transaction(
            function() use($address, $params) {
                return $address->update($params);
            }
        );

        session()->forget('nav-account');
        session()->put('nav-account', 'address');
        Alert::success("Success", "Update selected address");

        return redirect()->route('ecommerce.profile.index');
    }

    public function deleteAddress($id)
    {
        $address = Address::findOrFail($id);
        // return $address->member_id;
        if($address) $this->updateIsDefaultAddressToTrue($address->member_id);
        $address->delete();

        session()->forget('nav-account');
        session()->put('nav-account', 'address');
        Alert::success("Success", "Delete selected address");

        return back();
    }

    public function updateIsDefaultAddressToFalse($id)
    {
        $address = Address::where([
                        ['member_id', $id],
                        ['is_default', 1]
                    ])
                    ->first();

        if ($address)
        {
            $address->update([
                'is_default' => 0
            ]);
        }

        return true;
    }

    public function updateIsDefaultAddressToTrue($member_id)
    {
        $address = Address::whereMemberId($member_id)->OrderBy('created_at')->first();
        $address->update([
            'is_default' => 1
        ]);

        return true;
    }
}
