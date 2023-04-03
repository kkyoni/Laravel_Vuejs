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
use App\Models\Faq;
use Event;
use App\Helpers\Helper;


class FaqController extends Controller
{
    protected $authLayout = '';
    protected $pageLayout = '';
    /**
     * * * * * * * * * * * Create a new controller instance.
     * * * * * * * * * * *
     * * * * * * * * * * @return void
     * * * * * * * * * */
    public function __construct(){
        $this->authLayout = 'admin.auth.';
        $this->pageLayout = 'admin.pages.faq.';
        $this->middleware('auth');
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Index FAQ
    ------------------------------------------------------------------------------------*/
    public function index(Builder $builder, Request $request){
        $faq = Faq::orderBy('id','desc');
        if (request()->ajax()) {
            return DataTables::of($faq->get())->addIndexColumn()
            ->editColumn('status', function (Faq $faq) {
                if($faq->status == 'active'){
                    return '<a href="javascript:void(0)" data-toggle="tooltip" title="Active" class="changeStatusRecord" data-id="'.$faq->id.'"><span class="label label-primary">Active</span></a>';
                }else{
                    return '<a href="javascript:void(0)" data-toggle="tooltip" title="InActive" class="changeStatusRecord" data-id="'.$faq->id.'"><span class="label label-danger">InActive</span></a>';
                }
            })
            ->editColumn('action', function (Faq $faq) {
                $action  = '';
                    $action .= '<a class="btn btn-warning btn-circle btn-sm" href='.route('admin.faq.edit',[$faq->id]).'  data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>';
                    $action .='<a class="btn btn-danger btn-circle btn-sm m-l-10 deletefaq ml-1 mr-1" data-id ="'.$faq->id.'" href="javascript:void(0)" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></a>';
                    $action .='<a href="javascript:void(0)" class="btn btn-primary btn-circle btn-sm Showfaq" data-id="'.$faq->id.'" data-toggle="tooltip" title="Show"><i class="fa fa-eye"></i></a>';
                return $action;
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'DT_RowIndex', 'name' => '', 'title' => 'NO','width'=>'5%',"orderable" => false, "searchable" => false],
            ['data' => 'category', 'name' => 'category', 'title' => 'CATEGORY','width'=>'10%'],
            ['data' => 'status', 'name' => 'status', 'title' => 'STATUS','width'=>'10%'],
            ['data' => 'action', 'name' => 'action', 'title' => 'ACTION','width'=>'10%',"orderable" => false, "searchable" => false],
        ])
        ->parameters([ 'order' =>[] ]);
        return view($this->pageLayout.'index',compact('html'));
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Create FAQ
    ------------------------------------------------------------------------------------*/
    public function create(){
        return view($this->pageLayout.'create');
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Store FAQ
    ------------------------------------------------------------------------------------*/
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'category' => 'required|unique:faq,category',
            'status' => 'required',
        ]);
        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
        try{
            $faq = Faq::create(['category' => @$request->get('category'),'status' => @$request->get('status')]);
            Notify::success('FAQ Created',$title = "Successfully..!");
            return redirect()->route('admin.faq.index');
        }catch(\Exception $e){
            return back()->with(['alert-type' => 'danger','message' => $e->getMessage()]);
        }
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Edit FAQ
    ------------------------------------------------------------------------------------*/
    public function edit($id){
        $faq = Faq::where('id',$id)->first();
        if(!empty($faq)){
            return view($this->pageLayout.'edit',compact('faq'));
        }else{
            return redirect()->route('admin.faq.index');
        }
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Update FAQ
    ------------------------------------------------------------------------------------*/
    public function update(Request $request,$id){
        $validatedData = $request->validate([
            'category' => 'required|min:1|max:60|unique:faq,category,'.$id
        ]);
        try{
            Faq::where('id',$id)->update(['category' => @$request->get('category'),'status' => @$request->get('status')]);
            Notify::success('FAQ Updated',$title = "Successfully..!");
            return redirect()->route('admin.faq.index');
        } catch(\Exception $e){
            return back()->with(['alert-type' => 'danger','message' => $e->getMessage()]);
        }
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Delete FAQ
    ------------------------------------------------------------------------------------*/
    public function delete($id){
        try{
            $faq = Faq::where('id',$id)->first();
            $faq->delete();
            Notify::success('FAQ Deleted',$title = "Successfully..!");
            return response()->json(['status' => 'success','title' => 'Success!!','message' => 'FAQ Deleted Successfully..!']);
        }catch(\Exception $e){
            return back()->with(['alert-type' => 'danger','message' => $e->getMessage()]);
        }
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for show FAQ
    ------------------------------------------------------------------------------------*/
    public function show(Request $request) {
        $faq = Faq::where('id',$request->id)->first();
        return view($this->pageLayout.'show',compact('faq'));
    }

    /*-----------------------------------------------------------------------------------
    @Description: Function for Change Status FAQ
    ------------------------------------------------------------------------------------*/
    public function change_status(Request $request){
        try{
            $faq = Faq::where('id',$request->id)->first();
            if($faq->status == "active"){
                Faq::where('id',$request->id)->update(['status' => "inactive"]);
            }else{
                Faq::where('id',$request->id)->update(['status'=> "active"]);
            }
            Notify::success('FAQ Status Updated',$title = "Successfully..!");
            return response()->json(['status' => 'success','title' => 'Success!!','message' => 'FAQ Status Updated Successfully..!']);
        }catch (Exception $e){
            return response()->json(['status' => 'error','title' => 'Error!!','message' => $e->getMessage()]);
        }
    }
}
