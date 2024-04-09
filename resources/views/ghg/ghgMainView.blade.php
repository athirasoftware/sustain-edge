@include('includes.header')
<?php

use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

$userCompany = Company::find(Auth::user()->company_id);

$loginUserId = Crypt::encrypt(Auth::user()->id);
?>
<input type="hidden" name="financialYearVal" id="financialYearVal" value="{{ $financialYearVal }}" />
<div id="pageWrapper" class="cmnPage sidebarPage">
    <section id="scope">
        <div class="container">
            <div class="titleBx">
                <div class="icon">
                    <img src="assets/images/logo.png" alt="">
                </div>
                <h2 id="sedge-sub-head">GHG Emissions Calculator</h2>
            </div>
            {{ Form::hidden('loginUserId', $loginUserId, ['id' => 'loginUserId', 'name' => 'loginUserId']) }}
            <div class="cmnBx">
                <div class="flx">
                    <div class="lefBx">
                        <div class="name">Hello @if (isset(Auth::user()->full_name) && Auth::user()->full_name != '')
                                {{ Auth::user()->full_name }}
                            @else
                                'Name N/A'
                            @endif
                            @if (Auth::User()->hasRole('Administrator'))
                                (Admin),
                            @elseif(Auth::User()->hasRole('Team Lead'))
                                (Team Lead),
                            @elseif(Auth::User()->hasRole('Employee'))
                                (Employee),
                            @else
                                ,
                            @endif
                        </div>
                        <div class="cmpny">
                            @if (isset($userCompany->name_of_org) && $userCompany->name_of_org != '')
                                {{ $userCompany->name_of_org }}
                            @else
                                'Company N/A'
                            @endif
                        </div>
                        <a href="{{ route('logout') }}" class="links link-text">Logout</a>
                        {{ Form::hidden('encryptedUserId', isset(Auth::user()->id) && Auth::user()->id != '' ? Crypt::encrypt(Auth::user()->id) : '', ['id' => 'encryptedUserId']) }}
                        <div id="questionariesDiv">
                            <div class="sideBox">
                                @include('includes.sideBar')
                            </div>
                        </div>
                    </div>
                    <div class="rtsec">
                        <div class="topBox">
                            @include('ghg.ghgSubHead')
                            <!-- accordion starts here -->
                            <div id="ghgDiv">

                            </div>
                            <!-- accordion header -->
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
@include('includes.footer')

<script type="text/javascript">
    $(document).ready(function() {
        // var activeBtn = 'usersBtn';
        var activeBtn = 'questionnaireBtn';
        setTimeout(() => {
            $('#' + activeBtn).trigger('click');
        }, 500);
        $('#sedge-sub-head').html('');
        $('#questionnaireBtn').click(function(event) {
            $('#sedge-sub-head').html('GHG Emissions Calculator');
            activeBtn = 'questionnaireBtn';
            loadGHGViewByDivId(activeBtn);
        });

        $('#usersBtn').click(function(event) {
            $('#sedge-sub-head').html('Users');
            activeBtn = 'usersBtn';
            loadGHGViewByDivId(activeBtn);
        });


        $('#reportBtn').click(function(event) {
            $('#sedge-sub-head').html('');
            activeBtn = 'reportBtn';
            loadGHGViewByDivId(activeBtn);
        });
        $('#settingsBtn').click(function(event) {
            $('#sedge-sub-head').html('User Settings');
            activeBtn = 'settingsBtn';
            loadGHGViewByDivId(activeBtn);
        });
        $('#gobackBtn').click(function(event) {
            window.location.href = "{{ route('home') }}";
        });

        /* $('.usersSubList').click(function(event){
            var activeSubBtn = $(event).attr("id");
            // console.log("clicked on "+activeSubBtn);
            loadGHGViewBySubDivId(activeSubBtn);
            
        }); */
    });

    function loadGHGViewByDivId(activeBtn) {
        $('.accordion-button').addClass('collapsed');
        $('#' + activeBtn).removeClass('collapsed');
        $('.usersSubList').removeClass('active');
        $("#ghgDiv").html('');
        if (activeBtn == 'usersBtn') {
            $('#allUsersBtn').addClass('active');
            $("#userSideBarDiv").show();
            $("#quetionnaireSideBarDiv").hide();
            var url = "{{ URL::TO('ghgUserList') }}";
        } else if (activeBtn == 'questionnaireBtn') {
            $("#userSideBarDiv").hide();
            $("#quetionnaireSideBarDiv").show();
            var url = "{{ URL::TO('getGHGQuestionnaire') }}";
        } else if (activeBtn == 'reportBtn') {

            $("#userSideBarDiv").hide();
            $("#quetionnaireSideBarDiv").hide();
            $("#reportSideBarDiv").show();
            var url = "{{ URL::TO('reports') }}";
        } else if (activeBtn == 'settingsBtn') {
            $("#userSideBarDiv").hide();
            $("#quetionnaireSideBarDiv").hide();
            var loginUserId = $('#loginUserId').val();
            if (loginUserId == '' || typeof loginUserId === 'undefined') {
                bootbox.alert("Invalid User Found!");
            }
            var url = "{{ URL::TO('/ghgUserEditScreen') }}/" + loginUserId;
        }
        if (url != '' && typeof url !== 'undefined') {
            $("#ghgDiv").load(url);
        }
    }


    function loadGHGViewBySubDivId(event) {

        var activeSubBtn = $(event).attr("id");
        $("#ghgDiv").html('');
        $('.usersSubList').removeClass('active');
        // console.log("clicked on "+activeSubBtn);
        $('#' + activeSubBtn).addClass('active');
        if (activeSubBtn == 'allUsersBtn') {
            var url = "{{ URL::TO('ghgUserList') }}";
        } else if (activeSubBtn == 'addNewUsersBtn') {
            var url = "{{ URL::TO('addGHGNewUser') }}";
        }
        if (url != '' && typeof url !== 'undefined') {
            $("#ghgDiv").load(url);
        }
    }
</script>
