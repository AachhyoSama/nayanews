<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CancelledVoucher;
use App\Models\ChildAccount;
use App\Models\JournalImage;
use App\Models\FiscalYear;
use App\Models\JournalExtra;
use App\Models\JournalVouchers;
use App\Models\Setting;
use App\Models\Vendor;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use PDF;
use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class JournalVouchersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->user()->can('view-journals'))
        {
            $date = date("Y-m-d");
            $nepalidate = datenep($date);
            $exploded_date = explode("-", $nepalidate);

            if ($exploded_date[1] > 3) {
                $new_fiscal_year = $exploded_date[0].'/'.($exploded_date[0] + 1);
                $existing_fiscal_year = FiscalYear::where('fiscal_year', $new_fiscal_year)->first();

                if(!$existing_fiscal_year) {
                    $fiscal_year = FiscalYear::create([
                        'fiscal_year' => $new_fiscal_year
                    ]);
                    $fiscal_year->save();
                }
            }

            if ($request->ajax())
            {
                $current_year = FiscalYear::latest()->first();
                $data = JournalVouchers::latest()->where('fiscal_year_id', $current_year->id)->where('is_cancelled', '0');
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('particulars', function($row)
                        {
                            $particulars = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                $child_account = ChildAccount::where('id', $jextra->child_account_id)->first();
                                $particulars = $particulars . $child_account->title. '<br>' ;
                            }
                            return $particulars;
                        })
                        ->addColumn('debit_amount', function($row)
                        {
                            $debit_amounts = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                if ($jextra->debitAmount == 0) {
                                    $debit_amounts = $debit_amounts . '-'.'<br>' ;
                                } else {
                                    $debit_amounts = $debit_amounts .  'Rs. ' . $jextra->debitAmount.'<br>' ;
                                }
                            }
                            return $debit_amounts;
                        })
                        ->addColumn('narration', function($row)
                        {
                            $narration = '( '. $row->narration . ' )';
                            return $narration;
                        })
                        ->addColumn('credit_amount', function($row)
                        {
                            $credit_amounts = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                if ($jextra->creditAmount == 0) {
                                    $credit_amounts = $credit_amounts . '-'.'<br>' ;
                                } else {
                                    $credit_amounts = $credit_amounts .  'Rs. ' . $jextra->creditAmount.'<br>' ;
                                }
                            }
                            return $credit_amounts;
                        })
                        ->addColumn('status', function($row)
                        {
                            if($row->status == '1'){
                                $status = "Approved";
                            }else{
                                $status = "Awating for Approval";
                            }
                            return $status;
                        })
                        ->addColumn('action', function($row)
                        {
                            $showurl = route('journals.show', $row->id);
                            $editurl = route('journals.edit', $row->id);
                            $statusurl = route('journals.status', $row->id);
                            $cancellationurl = route('journals.cancel', $row->id);
                            if($row->status == 1){
                                $btnname = 'fa fa-thumbs-down';
                                $btnclass = 'btn-info';
                                $title = 'Disapprove';
                            }else{
                                $btnname = 'fa fa-thumbs-up';
                                $btnclass = 'btn-info';
                                $title = 'Approve';
                            }
                            $csrf_token = csrf_token();
                            $btn = "<a href='$showurl' class='edit btn btn-success btn-sm mt-1'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                <a href='$editurl' class='edit btn btn-primary btn-sm mt-1' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                <button type='button' class='btn btn-danger btn-sm mt-1' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                <form action='$statusurl' method='POST' style='display:inline-block'>
                                <input type='hidden' name='_token' value='$csrf_token'>
                                    <button type='submit' name = '$title' class='btn $btnclass btn-sm mt-1' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                </form>
                                <!-- Modal -->
                                    <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLabel'>Journal Voucher Cancellation</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                            </div>
                                            <div class='modal-body'>
                                                <p>Please give reason for Cancellation</p>
                                                <hr>
                                                <form action='$cancellationurl' method='POST'>
                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                    <input type='hidden' name='journalvoucher_id' value='$row->id'>
                                                    <div class='form-group'>
                                                        <label for='reason'>Reason:</label>
                                                        <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                    </div>
                                                    <div class='form-group'>
                                                        <label for='description'>Description: </label>
                                                        <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                    </div>
                                                    <button type='submit' name='submit' class='btn btn-danger'>Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                ";
                            return $btn;
                        })
                        ->rawColumns([ 'particulars', 'debit_amount' ,'credit_amount', 'narration', 'status', 'action'])
                        ->make(true);
            }
            $current_fiscal_year = FiscalYear::latest()->first();
            $actual_year = explode("/", $current_fiscal_year->fiscal_year);
            $fiscal_years = FiscalYear::all();
            return view('backend.journalvoucher.index', compact('fiscal_years', 'current_fiscal_year', 'actual_year'));
        }else{
            return view('backend.permission.permission');
        }

    }

    public function status($id, Request $request)
    {
        if($request->user()->can('approve-journals'))
        {
            if(isset($_POST['Disapprove']))
            {
                $journal = JournalVouchers::findorfail($id);
                $journal->update([
                    'status'=>'0',
                ]);
                $journal->save;
                return redirect()->route('journals.index')->with('success', 'Status Updated Successfully');
            }
            else if(isset($_POST['Approve']))
            {
                $user = Auth::user()->id;
                $journal = JournalVouchers::findorfail($id);
                $journal->update([
                    'status'=>'1',
                    'approved_by'=>$user,
                ]);
                $journal->save;
                return redirect()->route('journals.index')->with('success', 'Status Updated Successfully');
            }
        }
        else
        {
            return view('backend.permission.permission');
        }

    }

    public function cancel(Request $request, $id)
    {
        if($request->user()->can('cancel-journals')){
            $this->validate($request,[
                'reason'=>'required',
                'description'=>'required',
            ]);
            $user = Auth::user()->id;
            $cancellation = CancelledVoucher::create([
                'journalvoucher_id'=>$id,
                'reason'=>$request['reason'],
                'description'=>$request['description'],
            ]);

            $journal = JournalVouchers::findorfail($id);
                $journal->update([
                    'is_cancelled'=>'1',
                    'cancelled_by'=>$user,
                ]);
            $cancellation->save;
            $journal->save;
            return redirect()->route('journals.index')->with('success', 'Journal Voucher Successfully Cancelled');
        }
        else
        {
            return view('backend.permission.permission');
        }
    }

    public function revive($id, Request $request)
    {
        if($request->user()->can('approve-journals')){
            $journal = JournalVouchers::findorfail($id);
            $cancelledjournal = CancelledVoucher::where('journalvoucher_id', $id)->first();
            $cancelledjournal->delete();
                $journal->update([
                    'is_cancelled'=>'0',
                ]);
                $journal->save;
                return redirect()->route('journals.index')->with('success', 'Journal Voucher Successfully Revived');
        }
        else
        {
            return view('backend.permission.permission');
        }

    }

    public function cancelledindex(Request $request)
    {
        if($request->user()->can('cancel-journals')){
            if ($request->ajax()) {
                $data = JournalVouchers::latest()->with('journal_extras')->where('is_cancelled', '1')->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('particulars', function($row) {
                            $particulars = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                $child_account = ChildAccount::where('id', $jextra->child_account_id)->first();
                                $particulars = $particulars . $child_account->title. '<br>' ;
                            }
                            return $particulars;
                        })
                        ->addColumn('debit_amount', function($row) {
                            $debit_amounts = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                if ($jextra->debitAmount == 0) {
                                    $debit_amounts = $debit_amounts . '-'.'<br>' ;
                                } else {
                                    $debit_amounts = $debit_amounts .  'Rs. ' . $jextra->debitAmount.'<br>' ;
                                }
                            }
                            return $debit_amounts;
                        })
                        ->addColumn('narration', function($row) {
                            $narration = '( '. $row->narration . ' )';
                            return $narration;
                        })
                        ->addColumn('credit_amount', function($row) {
                            $credit_amounts = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                if ($jextra->creditAmount == 0) {
                                    $credit_amounts = $credit_amounts . '-'.'<br>' ;
                                } else {
                                    $credit_amounts = $credit_amounts .  'Rs. ' . $jextra->creditAmount.'<br>' ;
                                }
                            }
                            return $credit_amounts;
                        })
                        ->addColumn('status', function($row){
                            if($row->status == '1'){
                                $status = "Approved";
                            }else{
                                $status = "Awating for Approval";
                            }
                            return $status;
                        })
                        ->addColumn('action', function($row){
                            $showurl = route('journals.show', $row->id);
                            $editurl = route('journals.edit', $row->id);
                            $reviveurl = route('journals.revive', $row->id);
                            $statusurl = route('journals.status', $row->id);
                            if($row->status == 1){
                                $btnname = 'fa fa-thumbs-down';
                                $btnclass = 'btn-info';
                                $title = 'Disapprove';
                            }else{
                                $btnname = 'fa fa-thumbs-up';
                                $btnclass = 'btn-info';
                                $title = "Approve";
                            }
                            $csrf_token = csrf_token();
                        $btn = "<a href='$showurl' class='edit btn btn-success btn-sm' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                        <a href='$editurl' class='edit btn btn-primary btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                <form action='$reviveurl' method='POST' style='display:inline-block'>
                                <input type='hidden' name='_token' value='$csrf_token'>
                                    <button type='submit' class='btn btn-danger btn-sm text-light' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                </form>
                                <form action='$statusurl' method='POST' style='display:inline-block'>
                                <input type='hidden' name='_token' value='$csrf_token'>
                                    <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                </form>
                                ";

                            return $btn;
                        })
                        ->rawColumns([ 'particulars', 'debit_amount' ,'credit_amount', 'narration', 'status', 'action'])
                        ->make(true);
            }
            return view('backend.journalvoucher.cancelledindex');
        }else{
            return view('backend.permission.permission');
        }
    }

    public function unapprovedindex(Request $request)
    {
        if($request->user()->can('cancel-journals')){
            if ($request->ajax()) {
                $data = JournalVouchers::latest()->with('journal_extras')->where('status', '0')->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('particulars', function($row) {
                            $particulars = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                $child_account = ChildAccount::where('id', $jextra->child_account_id)->first();
                                $particulars = $particulars . $child_account->title. '<br>' ;
                            }
                            return $particulars;
                        })
                        ->addColumn('debit_amount', function($row) {
                            $debit_amounts = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                if ($jextra->debitAmount == 0) {
                                    $debit_amounts = $debit_amounts . '-'.'<br>' ;
                                } else {
                                    $debit_amounts = $debit_amounts .  'Rs. ' . $jextra->debitAmount.'<br>' ;
                                }
                            }
                            return $debit_amounts;
                        })
                        ->addColumn('narration', function($row) {
                            $narration = '( '. $row->narration . ' )';
                            return $narration;
                        })
                        ->addColumn('credit_amount', function($row) {
                            $credit_amounts = '';
                            $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                            foreach($jextras as $jextra){
                                if ($jextra->creditAmount == 0) {
                                    $credit_amounts = $credit_amounts . '-'.'<br>' ;
                                } else {
                                    $credit_amounts = $credit_amounts .  'Rs. ' . $jextra->creditAmount.'<br>' ;
                                }
                            }
                            return $credit_amounts;
                        })
                        ->addColumn('status', function($row){
                            if($row->status == '1'){
                                $status = "Approved";
                            }else{
                                $status = "Awating for Approval";
                            }
                            return $status;
                        })
                        ->addColumn('action', function($row){
                            $showurl = route('journals.show', $row->id);
                            $editurl = route('journals.edit', $row->id);
                            $reviveurl = route('journals.revive', $row->id);
                            $statusurl = route('journals.status', $row->id);
                            if($row->status == 1){
                                $btnname = 'fa fa-thumbs-down';
                                $btnclass = 'btn-info';
                                $title = 'Disapprove';
                            }else{
                                $btnname = 'fa fa-thumbs-up';
                                $btnclass = 'btn-info';
                                $title = "Approve";
                            }
                            $csrf_token = csrf_token();
                        $btn = "<a href='$showurl' class='edit btn btn-success btn-sm' data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                        <a href='$editurl' class='edit btn btn-primary btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                <form action='$reviveurl' method='POST' style='display:inline-block'>
                                <input type='hidden' name='_token' value='$csrf_token'>
                                    <button type='submit' class='btn btn-danger btn-sm text-light' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-smile-beam'></i></button>
                                </form>
                                <form action='$statusurl' method='POST' style='display:inline-block'>
                                <input type='hidden' name='_token' value='$csrf_token'>
                                    <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                </form>
                                ";

                            return $btn;
                        })
                        ->rawColumns([ 'particulars', 'debit_amount' ,'credit_amount', 'narration', 'status', 'action'])
                        ->make(true);
            }
            return view('backend.journalvoucher.unapproved');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->user()->can('create-journals')){
            $today = date("Y-m-d");
            $nepalitoday = datenep($today);

            $explode = explode('-', $nepalitoday);
            // dd($explode);
            $year = $explode[0];
            $month = $explode[1];

            if($month < 4){
                $fiscalyear = ($year - 1).'/'.$year;

            }else{
                $fiscalyear = $year.'/'.($year + 1);
            }

            $fiscal_year = FiscalYear::where('fiscal_year', $fiscalyear)->first();
            $journals = JournalVouchers::latest()->where('fiscal_year_id', $fiscal_year->id)->get();
            if(count($journals)==0){
                $jvnumber = "1";
            }else{
                $journal = JournalVouchers::latest()->first();
                $jv = $journal->journal_voucher_no;
                $arr = explode('-', $jv);
                $jvnumber = $arr[1] + 1;
            }
            $accounts = Account::latest()->get();
            $vendors = Vendor::all();
            return view('backend.journalvoucher.create', compact('accounts', 'jvnumber', 'vendors'));
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->user()->can('create-journals')){
            $user = Auth::user()->id;
            // dd($user);
            $this->validate($request, [
                'journal_voucher_no' => 'required',
                'entry_date' => 'required',
                'child_account_id' => 'required',
                'code' => 'required',
                'remarks' => '',
                'debitAmount' => '',
                'creditAmount' => '',
                'debitTotal' => 'required',
                'creditTotal' => 'required',
                'narration' => 'required',
                'file'=>'',
                'file.*' => 'mimes:png,jpg,jpeg',
                'vendor_id'=>'',
            ]);

            $date_array = explode("-", $request['entry_date']);

            if ($date_array[1] < 4) {
                $last_year = $date_array[0] - 1;
                $fiscal_year = $last_year . "/" . $date_array[0];
            } else {
                $next_year = $date_array[0] + 1;
                $fiscal_year = $date_array[0] . "/" . $next_year;
            }

            $available_fiscal_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();

            if ($available_fiscal_year) {
                $fiscal_year_id = $available_fiscal_year->id;
            } else {
                $new_fiscal_year = FiscalYear::create([
                    'fiscal_year' => $fiscal_year
                ]);
                $fiscal_year_id = $new_fiscal_year->id;
            }

            $dateAd = dateeng($request['entry_date']);
            $journal_voucher = JournalVouchers::create([
                'journal_voucher_no' => $request['journal_voucher_no'],
                'entry_date_english' => $dateAd,
                'entry_date_nepali' => $request['entry_date'],
                'fiscal_year_id' => $fiscal_year_id,
                'debitTotal' => $request['debitTotal'],
                'creditTotal' => $request['creditTotal'],
                'narration' => $request['narration'],
                'is_cancelled'=>'0',
                'vendor_id'=>$request['vendor_id'],
                'entry_by'=> $user,
            ]);
            $childaccounts = $request['child_account_id'];
            $codes = $request['code'];
            $remarks = $request['remarks'];
            $debitAmounts = $request['debitAmount'];
            $creditAmounts = $request['creditAmount'];
            $count = count($debitAmounts);
            for($x = 0; $x < $count; $x++){
                $journal_extra = JournalExtra::create([
                    'journal_voucher_id'=>$journal_voucher['id'],
                    'child_account_id'=>$childaccounts[$x],
                    'code'=>$codes[$x],
                    'remarks'=>$remarks[$x],
                    'debitAmount'=>$debitAmounts[$x],
                    'creditAmount'=>$creditAmounts[$x],
                ]);
                $journal_extra->save();
            }

            $imagename = '';
            if($request->hasfile('file')) {
                $images = $request->file('file');
                foreach($images as $image){
                    $imagename = $image->store('jv_images', 'uploads');
                    $journalimage = JournalImage::create([
                        'journalvoucher_id' => $journal_voucher['id'],
                        'location' => $imagename,
                    ]);
                    $journalimage->save();
                }
            }

            $journal_voucher->save();

            return redirect()->route('journals.index')->with('success', 'Journal Entry is successfully inserted.');
        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JournalVouchers  $journalVouchers
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if($request->user()->can('view-journals')){
            $journalVoucher = JournalVouchers::with('journal_extras')->findorFail($id);
            $journal_extras = JournalExtra::where('journal_voucher_id', $journalVoucher->id)->get();

            $created_at = date("Y-m-d", strtotime($journalVoucher->created_at));
            $created_nepali_date = datenep($created_at);

            return view('backend.journalvoucher.view', compact('journalVoucher', 'journal_extras', 'created_nepali_date'));
        }else{
            return view('backend.permission.permission');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JournalVouchers  $journalVouchers
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if($request->user()->can('edit-journals')){
            $journalVouchers = JournalVouchers::findorFail($id);
            if($journalVouchers->editcount==0){
                $today = date("Y-m-d");
                $nepalientrydate = $journalVouchers->entry_date_nepali;
                $nepalitoday = datenep($today);

                $explode = explode('-', $nepalitoday);
                $explodeentry = explode('-', $nepalientrydate);
                // dd($explode);
                $year = $explode[0];
                $month = $explode[1];
                $entryyear = $explodeentry[0];
                $entrymonth = $explodeentry[1];
                if($year == $entryyear && $month == $entrymonth){
                    $journal_extras = JournalExtra::where('journal_voucher_id', $id)->get();
                    $journalimage = JournalImage::where('journalvoucher_id', $id)->get();
                    $vendors = Vendor::all();
                    $accounts = Account::latest()->get();
                    return view('backend.journalvoucher.edit', compact('journalVouchers', 'journal_extras', 'journalimage','vendors', 'accounts'));
                }else{
                    return redirect()->route('journals.index')->with('error', "Can't edit the Entry from previous month");
                }
            }else{
                return redirect()->route('journals.index')->with('error', 'Journal Entry is cant be edited more then once.');
            }

        }else{
            return view('backend.permission.permission');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JournalVouchers  $journalVouchers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()->can('edit-journals')){
            $user = Auth::user()->id;
            $journal_voucher = JournalVouchers::findorFail($id);

            if(isset($_POST['save'])){
                $this->validate($request, [
                    'journal_voucher_no' => 'required',
                    'entry_date' => 'required',
                    'child_account_id' => 'required',
                    'code' => 'required',
                    'remarks' => '',
                    'debitAmount' => '',
                    'creditAmount' => '',
                    'debitTotal' => 'required',
                    'creditTotal' => 'required',
                    'narration' => 'required',
                ]);

                $date_array = explode("-", $request['entry_date']);

                    if ($date_array[1] < 4) {
                        $last_year = $date_array[0] - 1;
                        $fiscal_year = $last_year . "/" . $date_array[0];
                    } else {
                        $next_year = $date_array[0] + 1;
                        $fiscal_year = $date_array[0] . "/" . $next_year;
                    }

                    $available_fiscal_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();

                    if ($available_fiscal_year) {
                        $fiscal_year_id = $available_fiscal_year->id;
                    } else {
                        $new_fiscal_year = FiscalYear::create([
                            'fiscal_year' => $fiscal_year
                        ]);
                        $fiscal_year_id = $new_fiscal_year->id;
                    }

                    $dateAd = dateeng($request['entry_date']);

                $journal_voucher->update([
                    'journal_voucher_no' => $request['journal_voucher_no'],
                    'entry_date_english' => $dateAd,
                    'entry_date_nepali' => $request['entry_date'],
                    'fiscal_year_id' => $fiscal_year_id,
                    'debitTotal' => $request['debitTotal'],
                    'creditTotal' => $request['creditTotal'],
                    'narration' => $request['narration'],
                    'vendor_id'=>$request['vendor_id'],
                    'edited_by'=>$user,
                    'editcount'=>'1',
                ]);
                $journal_extra = JournalExtra::where('journal_voucher_id', $id)->get();
                foreach($journal_extra as $jextra){
                    $jextra->delete();
                }
                    $childaccounts = $request['child_account_id'];
                    $codes = $request['code'];
                    $remarks = $request['remarks'];
                    $debitAmounts = $request['debitAmount'];
                    $creditAmounts = $request['creditAmount'];
                    $count = count($debitAmounts);



                for($x = 0; $x < $count; $x++){
                    $journal_extra= JournalExtra::create([
                        'journal_voucher_id'=>$id,
                        'child_account_id'=>$childaccounts[$x],
                        'code'=>$codes[$x],
                        'remarks'=>$remarks[$x],
                        'debitAmount'=>$debitAmounts[$x],
                        'creditAmount'=>$creditAmounts[$x],
                    ]);
                    $journal_extra->save();
                }

            }
            elseif(isset($_POST['update'])){
                // dd($request['file']);
                $this->validate($request, [
                    'file'=>'',
                    'file*.' => 'mimes:png,jpg,jpeg',
                ]);
                $imagename = '';
                if($request->hasfile('file')) {
                    $images = $request->file('file');
                    foreach($images as $image){
                        $imagename = $image->store('jv_images', 'uploads');
                        $journalimage = JournalImage::create([
                            'journalvoucher_id' => $journal_voucher['id'],
                            'location' => $imagename,
                        ]);
                        $journalimage->save();
                    }
                }
            }

            return redirect()->route('journals.index')->with('success', 'Journal Entry is successfully updated.');
        }else{
            return view('backend.permission.permission');
        }

    }

    public function trialbalance(Request $request)
    {
        // $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts', 'sub_accounts.child_accounts.journal_extras')->get();
        $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts')->get();
        // dd($mainaccounts);
        $fiscal_years = FiscalYear::all();
        $current_fiscal_year = FiscalYear::latest()->first();
        $actual_year = explode("/", $current_fiscal_year->fiscal_year);
        $fiscal_years = FiscalYear::all();
        return view('backend.journalvoucher.trialbalance', compact('current_fiscal_year', 'mainaccounts', 'actual_year', 'fiscal_years'));
    }

    public function generatetrialreport(Request $request, $id, $starting_date, $ending_date)
    {
        // dd('hello');
        $start_date = dateeng($starting_date);
        $end_date = dateeng($ending_date);
        if ($start_date > $end_date) {
            return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
        }

        $start_date_explode = explode("-", $starting_date);
        $end_date_explode = explode("-", $ending_date);

        if(($end_date_explode[0]-$start_date_explode[0]) > 1) {
            return redirect()->back()->with('error', 'Select dates within a fiscal year.');
        }
        $current_year = FiscalYear::latest()->first();
        $mainaccounts = Account::with('sub_accounts','sub_accounts.child_accounts')->get();

        $current_fiscal_year = FiscalYear::where('id', $id)->first();
        $actual_year = explode("/", $current_fiscal_year->fiscal_year);
        $fiscal_years = FiscalYear::all();
        return view('backend.journalvoucher.trialbalancereport', compact('current_year', 'mainaccounts', 'fiscal_years', 'current_fiscal_year', 'actual_year','id', 'start_date', 'end_date', 'starting_date', 'ending_date'));
    }

    public function extra(Request $request)
    {
        if($request->user()->can('view-journals')){
            $fiscal_year = $request['fiscal_year'];
            $starting_date = $request['starting_date'];
            $ending_date = $request['ending_date'];
            $current_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();
            return redirect()->route('generatereport', ["id" => $current_year->id, "starting_date" => $starting_date, "ending_date" => $ending_date]);
        }else{
            return view('backend.permission.permission');
        }
    }

    public function trialextra(Request $request)
    {
        $fiscal_year = $request['fiscal_year'];
        $starting_date = $request['starting_date'];
        $ending_date = $request['ending_date'];
        $current_year = FiscalYear::where('fiscal_year', $fiscal_year)->first();
        return redirect()->route('generatetrialreport', ["id" => $current_year->id, "starting_date" => $starting_date, "ending_date" => $ending_date]);
    }

    public function generatereport(Request $request, $id, $starting_date, $ending_date)
    {
        if($request->user()->can('view-journals')){
                $start_date = dateeng($starting_date);
                $end_date = dateeng($ending_date);
                if ($start_date > $end_date) {
                    return redirect()->back()->with('error', 'Starting date cannot be greater than ending date.');
                }

                $start_date_explode = explode("-", $starting_date);
                $end_date_explode = explode("-", $ending_date);

                if(($end_date_explode[0]-$start_date_explode[0]) > 1) {
                    return redirect()->back()->with('error', 'Select dates within a fiscal year.');
                }

                if ($request->ajax()) {
                    // $current_year = FiscalYear::where('id', $id)->first();
                    $data = JournalVouchers::latest()->where('fiscal_year_id', $id)->where('entry_date_english', '>=', $start_date)->where('entry_date_english', '<=', $end_date)->where('is_cancelled', '0');
                    return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('particulars', function($row) {
                                $particulars = '';
                                $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                                foreach($jextras as $jextra){
                                    $child_account = ChildAccount::where('id', $jextra->child_account_id)->first();
                                    $particulars = $particulars . $child_account->title. '<br>' ;
                                }
                                return $particulars;
                            })
                            ->addColumn('entry_date', function($row) {
                                    $entry_date_nepali = $row->entry_date_nepali;
                                return $entry_date_nepali;
                            })
                            ->addColumn('debit_amount', function($row) {
                                $debit_amounts = '';
                                $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                                foreach($jextras as $jextra){
                                    if ($jextra->debitAmount == 0) {
                                        $debit_amounts = $debit_amounts . '-'.'<br>' ;
                                    } else {
                                        $debit_amounts = $debit_amounts .  'Rs. ' . $jextra->debitAmount.'<br>' ;
                                    }
                                }
                                return $debit_amounts;
                            })
                            ->addColumn('narration', function($row) {
                                $narration = '( '. $row->narration . ' )';
                                return $narration;
                            })
                            ->addColumn('credit_amount', function($row) {
                                $credit_amounts = '';
                                $jextras = JournalExtra::where('journal_voucher_id', $row->id)->get();
                                foreach($jextras as $jextra){
                                    if ($jextra->creditAmount == 0) {
                                        $credit_amounts = $credit_amounts . '-'.'<br>' ;
                                    } else {
                                        $credit_amounts = $credit_amounts .  'Rs. ' . $jextra->creditAmount.'<br>' ;
                                    }
                                }
                                return $credit_amounts;
                            })
                            ->addColumn('file', function($row){
                                if($row->file == null){
                                    $file = '/storage/uploads/noimage.jpg';
                                }else{
                                    $file = $row->file;
                                }

                                $image = "<img src='$file' style='height:100px;'>";
                                return $image;
                            })
                            ->addColumn('action', function($row){
                                $showurl = route('journals.show', $row->id);
                                $editurl = route('journals.edit', $row->id);
                                $statusurl = route('journals.status', $row->id);
                                $cancellationurl = route('journals.cancel', $row->id);
                                if($row->status == 1){
                                    $btnname = 'fa fa-thumbs-down';
                                    $btnclass = 'btn-info';
                                    $title = 'Disapprove';
                                }else{
                                    $btnname = 'fa fa-thumbs-up';
                                    $btnclass = 'btn-info';
                                    $title = 'Approve';
                                }
                                $csrf_token = csrf_token();
                            $btn = "<a href='$showurl' class='edit btn btn-success btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                    <a href='$editurl' class='edit btn btn-primary btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                    <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#cancellation' data-toggle='tooltip' data-placement='top' title='Cancel'><i class='fa fa-ban'></i></button>
                                    <form action='$statusurl' method='POST' style='display:inline-block'>
                                    <input type='hidden' name='_token' value='$csrf_token'>
                                        <button type='submit' name = '$title' class='btn $btnclass btn-sm' data-toggle='tooltip' data-placement='top' title='$title'><i class='$btnname'></i></button>
                                    </form>
                                    <!-- Modal -->
                                        <div class='modal fade text-left' id='cancellation' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalLabel'>Journal Voucher Cancellation</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <p>Please give reason for Cancellation</p>
                                                    <hr>
                                                    <form action='$cancellationurl' method='POST'>
                                                    <input type='hidden' name='_token' value='$csrf_token'>
                                                        <input type='hidden' name='journalvoucher_id' value='$row->id'>
                                                        <div class='form-group'>
                                                            <label for='reason'>Reason:</label>
                                                            <input type='text' name='reason' id='reason' class='form-control' placeholder='Enter Reason for Cancellation' required>
                                                        </div>
                                                        <div class='form-group'>
                                                            <label for='description'>Description: </label>
                                                            <textarea name='description' id='description' cols='30' rows='5' class='form-control' placeholder='Enter Detailed Reason' required></textarea>
                                                        </div>
                                                        <button type='submit' name='submit' class='btn btn-danger'>Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    ";

                                return $btn;
                            })
                            ->rawColumns([ 'particulars', 'debit_amount', 'entry_date', 'credit_amount', 'narration', 'file', 'action'])
                            ->make(true);
                }
                $current_fiscal_year = FiscalYear::where('id', $id)->first();
                $actual_year = explode("/", $current_fiscal_year->fiscal_year);
                $fiscal_years = FiscalYear::all();
                return view('backend.journalvoucher.report', compact('fiscal_years', 'current_fiscal_year', 'actual_year','id', 'starting_date', 'ending_date'));
        }else{
            return view('backend.permission.permission');
        }
    }

    public function printpreview($id)
    {
        $journalVoucher = JournalVouchers::with('journal_extras')->findorFail($id);
        $journal_extras = JournalExtra::where('journal_voucher_id', $journalVoucher->id)->get();

        $created_at = date("Y-m-d", strtotime($journalVoucher->created_at));
        $created_nepali_date = datenep($created_at);
        $setting = Setting::first();
        return view('backend.journalvoucher.printpreview', compact('journalVoucher', 'journal_extras', 'created_nepali_date', 'setting'));
    }

    public function generateJournalPDF($id)
    {
        $journalVoucher = JournalVouchers::with('journal_extras')->findorFail($id);
        $journal_extras = JournalExtra::where('journal_voucher_id', $journalVoucher->id)->get();
        $created_at = date("Y-m-d", strtotime($journalVoucher->created_at));
        $created_nepali_date = datenep($created_at);
        $setting = Setting::first();
        $opciones_ssl=array(
            "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
            ),
        );

        $img_path = 'uploads/' . $setting->logo;
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $data = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($data);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        // dd($path_img);

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('backend.journalvoucher.downloadjournal', compact('journalVoucher', 'journal_extras', 'created_nepali_date', 'setting', 'path_img'));
        return $pdf->download('journal_voucher.pdf');
    }
}
