<?php
namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DataTables,Notify,Str,Storage;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Auth;
use App\Models\User;
use Event;

class UsersController extends Controller{
    protected $authLayout = '';
    protected $pageLayout = '';
    /**
     * * * * Create a new controller instance.
     * * * *
     * * * * @return void
     * * * */
    public function __construct(){
        $this->authLayout = 'admin.auth.';
        $this->pageLayout = 'admin.pages.user.';
        $this->middleware('auth');
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Index User
    ------------------------------------------------------------------------------------*/
    public function index(Builder $builder, Request $request){
        $user = User::where('user_type','user')->orderBy('id','desc');
        if (request()->ajax()) {
            return DataTables::of($user->get())->addIndexColumn()
            ->editColumn('status', function (User $user) {
                if($user->status == 'active'){
                    return '<a href="javascript:void(0)" data-toggle="tooltip" title="Active" class="changeStatusRecord" data-id="'.$user->id.'"><span class="label label-primary">Active</span></a>';
                }else{
                    return '<a href="javascript:void(0)" data-toggle="tooltip" title="InActive" class="changeStatusRecord" data-id="'.$user->id.'"><span class="label label-danger">InActive</span></a>';
                }
            })
            ->editColumn('action', function (User $user) {
                $action  = '';
                $action .= '<a class="btn btn-warning btn-circle btn-sm" href='.route('admin.user.edit',[$user->id]).'  data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>';
                $action .='<a class="btn btn-danger btn-circle btn-sm m-l-10 deleteuser ml-1 mr-1" data-id ="'.$user->id.'" href="javascript:void(0)" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></a>';
                $action .='<a href="javascript:void(0)" class="btn btn-primary btn-circle btn-sm Showuser" data-id="'.$user->id.'" data-toggle="tooltip" title="Show"><i class="fa fa-eye"></i></a>';
                return $action;
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'DT_RowIndex', 'name' => '', 'title' => 'NO','width'=>'5%',"orderable" => false, "searchable" => false],
            ['data' => 'first_name', 'name' => 'first_name', 'title' => 'First Name','width'=>'10%'],
            ['data' => 'last_name', 'name' => 'last_name', 'title' => 'Last Name','width'=>'10%'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email','width'=>'10%'],
            ['data' => 'status', 'name' => 'status', 'title' => 'STATUS','width'=>'10%'],
            ['data' => 'action', 'name' => 'action', 'title' => 'ACTION','width'=>'10%',"orderable" => false, "searchable" => false],
        ])->parameters([ 'order' =>[] ]);
        return view($this->pageLayout.'index',compact('html'));
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Create User
    ------------------------------------------------------------------------------------*/
    public function create(){
        return view($this->pageLayout.'create');
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Store User
    ------------------------------------------------------------------------------------*/
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'password'              => 'required|min:8',
            'password_confirmation' => 'required|min:8',
            'first_name' => 'required',
            'last_name' => 'required',
            'email'    => 'required|unique:users,email',
            'dob' => 'required',
            'status' => 'required',
        ],[
            'first_name' => 'The First Name field is required.',
            'last_name' => 'The Last Namefield is required.',
            'email' => 'The Email field is required.',
            'dob' => 'The Date Of Brith field is required.',
            'status' => 'The Status field is required.',
            'password.required'              => 'The new password field is required.',
            'password_confirmation.required' => 'The confirm password field is required.'
        ]);
        $validatedData->after(function() use($validatedData,$request){
            if($request->get('password') !== $request->get('password_confirmation')){
                $validatedData->errors()->add('password_confirmation','The Confirm Password does not match.');
            }
        });
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }try{
            User::create(['first_name' => @$request->get('first_name'),'last_name' => @$request->get('last_name'),'email' => @$request->get('email'),'password' => \Hash::make($request->get('password')),'dob' => @$request->get('dob'),'status' => @$request->get('status'),'user_type'=>'user']);
            Notify::success('User Created',$title = "Successfully..!");
            return redirect()->route('admin.user.index');
        }catch(\Exception $e){
            return back()->with(['alert-type' => 'danger','message' => $e->getMessage()]);
        }
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Edit User
    ------------------------------------------------------------------------------------*/
    public function edit($id){
        $user = User::where('id',$id)->first();
        if(!empty($user)){
            return view($this->pageLayout.'edit',compact('user'));
        }else{
            return redirect()->route('admin.user.index');
        }
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Update User
    ------------------------------------------------------------------------------------*/
    public function update(Request $request,$id){
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email'    => 'required|unique:users,email,'.Auth::user()->id,
            'dob' => 'required',
            'status' => 'required',
        ]);
        try{
            User::where('id',$id)->update(['first_name' => @$request->get('first_name'),'last_name' => @$request->get('last_name'),'email' => @$request->get('email'),'dob' => @$request->get('dob'),'status' => @$request->get('status'),'user_type'=>'user']);
            Notify::success('User Updated',$title = "Successfully..!");
            return redirect()->route('admin.user.index');
        } catch(\Exception $e){
            return back()->with(['alert-type' => 'danger','message' => $e->getMessage()]);
        }
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Delete User
    ------------------------------------------------------------------------------------*/
    public function delete($id){
        try{
            $user = User::where('id',$id)->first();
            $user->delete();
            Notify::success('User Deleted',$title = "Successfully..!");
            return response()->json(['status' => 'success','title' => 'Success!!','message' => 'User Deleted Successfully..!']);
        }catch(\Exception $e){
            return back()->with(['alert-type' => 'danger','message' => $e->getMessage()]);
        }
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for show User
    ------------------------------------------------------------------------------------*/
    public function show(Request $request) {
        $user = User::where('id',$request->id)->first();
        return view($this->pageLayout.'show',compact('user'));
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Change Status User
    ------------------------------------------------------------------------------------*/
    public function change_status(Request $request){
        try{
            $user = User::where('id',$request->id)->first();
            if($user->status == "active"){
                User::where('id',$request->id)->update(['status' => "inactive"]);
            }else{
                User::where('id',$request->id)->update(['status'=> "active"]);
            }
            Notify::success('User Status Updated',$title = "Successfully..!");
            return response()->json(['status' => 'success','title' => 'Success!!','message' => 'User Status Updated Successfully..!']);
        }catch (Exception $e){
            return response()->json(['status' => 'error','title' => 'Error!!','message' => $e->getMessage()]);
        }
    }
}