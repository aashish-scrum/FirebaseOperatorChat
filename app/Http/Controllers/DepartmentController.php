<?php

namespace App\Http\Controllers;

use App\Jobs\InviteJob;
use App\Models\PendingInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function create()
    {
        return view('department-create');
    }

    public function invite_members(Request $request)
    {
        try {
            foreach ($request->email as $key => $email) {
                $invite = new PendingInvite();
                $invite->email = $email;
                $invite->token = Str::random(25);
                $invite->role = $request->role[$key];
                $invite->company_id = $this->companyUuid;
                $invite->save();
                $invite->refresh();
                InviteJob::dispatch($invite);
            }
            $request->session()->flash('success','Members Invited Successfully!');
        } catch (\Exception $e) {
            $request->session()->flash('error',$e->getMessage());
        }
        return redirect()->back();
    }
}
