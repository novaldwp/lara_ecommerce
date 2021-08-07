<?php

namespace App\Services;

use App\Models\Front\Address;
use App\Models\Front\Member;
use Hash;

class MemberService {

    private $member_id;

    public function __construct()
    {
        $this->member_id = auth()->guard('members')->user()->id;
    }

    public function updateMemberDetail($request, $id)
    {
        $member = Member::findOrFail($id);
        abort_if(!$member, 404);

        $member->first_name = $request->first_name;
        $member->last_name = $request->last_name;
        $member->phone  = $request->phone;
        $member->email  = $request->email;
        $member->save();

        return true;
    }

    public function updateMemberPassword($request, $id)
    {
        $member      = Member::findOrFail($id);
        abort_if(!$member, 404);

        if(!Hash::check($request->password, $member->password))
        {
            return false;
        }

        $member->password = bcrypt($request->password);
        $member->save();

        return true;
    }

    public function getMemberAddress()
    {
        $addresses = Address::where('member_id', $this->member_id)->orderBy('is_default', 'DESC')->get();

        return $addresses;
    }

    public function storeMemberAddress($request)
    {
        $address = Address::whereMemberId($this->member_id)->count();
        $params['member_id'] = $this->member_id;
        $params['name'] = $request->name;
        $params['postcode'] = $request->postcode;
        $params['street'] = $request->street;
        $params['is_default'] = $address == 0 ? 1 : ($request->is_default == 1 ? $this->updateIsDefaultAddressToFalse($this->member_id) : 0);
        $params['province_id'] = $request->province_id;
        $params['city_id'] = $request->city_id;

        $store = Address::create($params);

        return $store;
    }

    public function updateMemberAddress($request, $id)
    {

        $address = Address::whereId($id)->first();
        abort_if(!$address, 404);

        $params['member_id'] = $request->member_id;
        $params['name'] = $request->name;
        $params['postcode'] = $request->postcode;
        $params['street'] = $request->street;
        $params['is_default'] = $request->is_default == 1 ? $this->updateIsDefaultAddressToFalse($request->member_id) : 0;
        $params['province_id'] = $request->province_id;
        $params['city_id'] = $request->city_id;

        $update = $address->update($params);

        return $update;
    }

    public function getMemberAddressById($id)
    {

    }

    public function deleteMemberAddress($id)
    {
        $address = Address::findOrFail($id);
        if($address) $this->updateIsDefaultAddressToTrue($address->member_id);
        $address->delete();

        return $address;
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
