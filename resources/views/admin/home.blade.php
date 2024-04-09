@include('includes.header')
<?php
    use App\Models\Company;
    use Illuminate\Support\Facades\Session;
    // $userCompany =  \App\Models\User::find(Auth::user()->id)->company;
    $userCompany =  Company::find(Auth::user()->company_id);
?>
<div id="pageWrapper" class="planPage">
    <section id="plans">
        <div class="container">
            <div class="titleBx">
                <div class="icon">
	                <img src="assets/images/logo.png" alt="">
                </div>
                <h2 id="sedge-view-title">Admin Dashboard</h2>
            </div>
            <div class="cmnBx">
                <div class="flx">
                    <div class="lefBx">
                        <div class="name">Hello @if(isset(Auth::user()->full_name) && Auth::user()->full_name != '' ) {{ Auth::user()->full_name }}, @else 'Name N/A,' @endif</div>
                        <div class="cmpny">@if(isset($userCompany->name_of_org) && $userCompany->name_of_org != '' ) {{ $userCompany->name_of_org }} @else 'Company N/A'@endif </div>
                        <a href="{{ route('logout') }}" class="links logoutBtn link-text">Logout</a>
                        {{ Form::hidden('encryptedUserId', (isset(Auth::user()->id) && Auth::user()->id != '')?Crypt::encrypt(Auth::user()->id):'', [ 'id' => 'encryptedUserId']) }}
                    </div>
                    <div class="rtsec">
                        <div class="itemFx">
                            <a href="javascript:void(0)" class="lnBtn" id="chooseView">Choose</a>
                            <a href="javascript:void(0)" class="lnBtn" id="reportView">Report</a>
                            @if(Auth::User()->hasRole('Administrator'))
                                <a href="javascript:void(0)" class="lnBtn" id="usersView">Users</a>
                                <a href="javascript:void(0)" class="lnBtn" id="addNewUser">Add User&nbsp;<i class="fa fa-plus" style="font-size:12px;"></i></a>
                            @endif
                            <a href="javascript:void(0)" class="lnBtn" id="settingsView">Settings</a>
                        </div>
                    </div>
                </div>
                <span class="alert alert-success" id="success_status_msg" style="display:none"> </span>
                <span class="alert alert-danger" id="error_status_msg" style="display:none"> </span>
                <div id="adminDiv">
                    @include('admin.chooseView')
                </div>
            </div>
        </div>
    </section>
    <div id="getFinancialYear" class="modal right fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title">Checkout Financial Year</h4>
                            </div>
                            <div class="modal-body">
                                <div id="financialYearData">
                                    <?php 
                                        $pastYears = 5;
                                        $currentYear = date('Y');
                                        $startYear = date('Y') - $pastYears;
                                        $yearsArray = ['' => "Select FY"];
                                        for($i = $startYear; $i <= $currentYear; $i++ ) {
                                            $yearsArray[] = $startYear."-".$startYear+1;
                                            $startYear = $startYear+1;
                                        }
                                        rsort($yearsArray);

                                    ?>
                                    {{ Form::open(array('id' =>'financialYear', 'name' => 'financialYear')) }}
                                    <div class="md-form mb-2">   
                                        {{Form::select('FY', $yearsArray, '', array('id'=>'FY', "class" => 'form-control'))}}
                                    </div>
                                    <div class="md-form mb-2 error">
                                            <span id="errorMsg" class="error"></span>
                                            <span id="successMsg" class="success"></span>
                                    </div>
                                    <div class="md-form mb-2 ml-5">
                                        {{Form::button('OK', array('id'=>'getFY', "class" => 'btn btn-primary', 'onclick' =>'getFinancialYear()'))}}
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
</div>
@include('includes.footer')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        @if (session('success_status'))
            $('#success_status_msg').show();
            $('#success_status_msg').html("{{ session('success_status') }}");
            setTimeout(() => {
                $('#success_status_msg').html('');
                $('#success_status_msg').hide();
            }, 3000);
        @endif
        @if(session('error_status'))
            $('#error_status_msg').show();
            $('#error_status_msg').html("{{ session('error_status') }}");
            setTimeout(() => {
                $('#error_status_msg').html('');
                $('#error_status_msg').hide();
            }, 3000);
        @endif
        
        $('#chooseView').click(function(event){
            console.log("clicked on chooseView");
            $("#adminDiv").html('');
            $("#adminDiv").load("{{ route('getchooseView') }}");
        });
        $('#reportView123').click(function(event){
            console.log("clicked on reportView");
            $("#adminDiv").html('');
            $("#adminDiv").load("{{ route('getchooseView') }}");
        });
        $('#usersView').click(function(event){
            console.log("clicked on usersView");
            $("#adminDiv").html('');
            $("#adminDiv").load("{{ route('getUsersView') }}");
        });
        $('#addNewUser').click(function(event){
            console.log("clicked on addNewUser");
            $("#adminDiv").html('');
            $("#adminDiv").load("{{ route('addNewUser') }}");
        });
        
        $('#settingsView').click(function(event){
            console.log("clicked on reportView");
            var encryptedUserId = $('#encryptedUserId').val();
            console.log(encryptedUserId);
            var urlUserEdit = "{{URL::TO('userEdit')}}?id="+encodeURI(encryptedUserId)+"&type=userUpdate";
            $("#adminDiv").html('');
            $("#adminDiv").load(urlUserEdit);
        });

        $('#ghgEmissionsCalculator').click(function(e){
            $('#getFinancialYear').modal('show');
        });
    });
    function getFinancialYear() {
        $('#errorMsg').html('');
        var financialYearVal = $('#FY').val();
        var financialYearVal = $("#FY option:selected").text();
        // console.log("financialYearVal =>"+financialYearVal);
        if(financialYearVal == '' || financialYearVal == 'Select FY' || typeof financialYearVal === 'undefined'){
            $('#errorMsg').html('');
            $('#errorMsg').html("Financial Year should not be empty");
            return false;
        }
        if(financialYearVal != '' && typeof financialYearVal !== 'undefined') {
            var url = "{{ url('ghgEmissionsView') }}";
            var SessionValForget = "{{ Session::forget('financialYearVal') }}";
            var financialYearSessionVal = "{{ Session::put('financialYearVal', "+financialYearVal+"); }}";
            // url = url+"?financialYearVal="+financialYearVal;
            location.href = url;
        }
    }
</script>       