<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
use Response;
class SmsController extends Controller
{
    protected $authLayout = '';
    protected $pageLayout = 'admin.pages.';
    public function __construct(){
        $this->authLayout = 'admin.auth.';
        $this->pageLayout = 'admin.pages.';
        $this->middleware('auth');
    }
    public function sms()
    {
        return view('admin.pages.sms');
    }
}
