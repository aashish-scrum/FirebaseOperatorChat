<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CompanyController extends Controller
{
    public function create()
    {
        $back = request()->server('HTTP_REFERER');
        return view('company-create',compact('back'));
    }

    public function save(Request $request)
    {
        $company = new Company();
        $company->uuid = Str::random(24);
        $company->user_id = auth('web')->id();
        $company->company_name = $request->company;
        $company->company_url = $request->site_url;
        $company->widget_name = $request->widget_name;
        $company->save();
        return redirect()->route('dashboard',$company->uuid);
    }
}
