<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Member\MemberAddressRequest;
use App\Http\Requests\Front\Member\MemberDetailRequest;
use App\Http\Requests\Front\Member\MemberPasswordRequest;
use App\Models\Front\Address;
use App\Models\Front\City;
use App\Models\Front\Province;
use App\Services\MemberService;
use RealRashid\SweetAlert\Facades\Alert;

class MemberController extends Controller
{
    public function index()
    {
        $member = auth()->guard('members')->user();

        return view('ecommerce.profile.dashboard.index', compact('member'));
    }

    public function account()
    {
        $member = auth()->guard('members')->user();

        return view('ecommerce.profile.account.index', compact('member'));
    }

    public function updateDetailMember(MemberDetailRequest $request, $id, MemberService $member)
    {
        $member = $member->updateMemberDetail($request, $id);

        if($member) Alert::success("Success", "Update account detail");

        return back();
    }

    public function updatePasswordMember(MemberPasswordRequest $request, $id, MemberService $member)
    {
        $member = $member->updateMemberPassword($request, $id);

        if ($member) {
            Alert::success("Success", "Update password account");
        }
        else {
            return back()->withErrors(['current_password' => 'Current password is wrong, please try again']);
        }

        return back();
    }

    public function address(MemberService $member)
    {
        $addresses = $member->getMemberAddress();

        return view('ecommerce.profile.address.index', compact('addresses'));
    }

    public function addAddress()
    {
        $provinces = Province::all();

        return view('ecommerce.profile.address.create', compact('provinces'));
    }

    public function storeAddress(MemberAddressRequest $request, MemberService $member)
    {
        $member = $member->storeMemberAddress($request);

        if($member) Alert::success("Success", "Added new address");

        return redirect()->route('ecommerce.profile.address');
    }

    public function editAddress($id)
    {
        $address = Address::whereId($id)->first();
        $provinces = Province::all();
        $cities = City::all();

        return view('ecommerce.profile.address.edit', compact('address', 'provinces', 'cities'));
    }

    public function updateAddress(MemberAddressRequest $request, $id, MemberService $member)
    {
        $address = $member->updateMemberAddress($request, $id);

        if($address) Alert::success("Success", "Update selected address");

        return redirect()->route('ecommerce.profile.address');
    }

    public function deleteAddress($id, MemberService $member)
    {
        $address = $member->deleteMemberAddress($id);

        if($address) Alert::success("Success", "Delete selected address");

        return back();
    }

}
