<?php
    use Illuminate\Support\Facades\Crypt;
    $roleChange = $delete = $edit = $deauthenticate = $isLead = $admin = false;
    if(Auth::User()->hasRole('Administrator')) {
        $admin = true;
    }
    if(Auth::User()->hasRole('Team Lead')) {
        $isLead = true;
    }
?>
<style> table tbody td a { font-size: 12px !important; } </style>
<div class="cmnBx userList">
                <div class="mainTitle">List of all users in your organisation</div>
                <table>
                    @if(isset($userData) && count($userData) > 0)
                       
                        
                        @foreach($userData as $user)
                            <tr>
                                <td>
                                    @if(isset($user->full_name) && $user->full_name != '')
                                        {{$user->full_name}}
                                    @else 
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if(isset($user->role) && $user->role != '')
                                        {{ $roles[$user->role] }}
                                            @if(($isLead && $user->role != 1) || $admin)
                                                <a href="javascript:void(0)"  onclick="getUserRoleModal('{{ Crypt::encrypt($user->id) }}');">change Role</a>
                                            @endif
                                    @else 
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if(isset($user->id) && $user->id != '')
                                        Authenticated 
                                        @if(($isLead && $user->role != 1) || $admin)
                                            <a href="javascript:void(0)" >de-authenticate</a>
                                            <a href="javascript:void(0)" onclick="deleteUser('{{ Crypt::encrypt($user->id) }}');">delete user</a>
                                            <a href="javascript:void(0)" onclick="editUser('{{ Crypt::encrypt($user->id) }}');">edit user</a>
                                        @endif
                                    @else 
                                        N/A
                                    @endif
                                </td>

                        @endforeach
                    @else
                        <tr><td colspan="3">No User Records Found</td></tr>
                    @endif
                </table>
                <div id="change_view_modal" class="modal right fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title">Update User Role</h4>
                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div id="change_view_data">
                                    <?php $roles[''] = 'Please Select Role'; 
                                       $modalRoles = $roles;
                                       if($isLead) {
                                            unset($modalRoles[1]); 
                                       }
                                    ?>
                                    {{ Form::open(array('id' =>'changeUserRole', 'name' => 'changeUserRole')) }}
                                    <div class="md-form mb-2">    
                                        {{Form::select('role', $modalRoles, '', array('id'=>'selectedRole', "class" => 'form-control'))}}
                                    </div>
                                    <div class="md-form mb-2">
                                        {{Form::hidden('userId', '', array('id' => 'userId', 'name' => 'userId'))}}
                                    </div>
                                    <div class="md-form mb-2 error">
                                            <span id="errorMsg" class="error"></span>
                                            <span id="successMsg" class="success"></span>
                                    </div>
                                    <div class="md-form mb-2 ">
                                        {{Form::button('UPDATE', array('id'=>'saveRoleForm', "class" => 'btn btn-primary', 'onclick' =>'checkAndUpdateRoleForm()'))}}
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
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    function getUserRoleModal(id) {
        if(id != '' || typeof id !== 'undefined'){
            $('#userId').val('');
            $('#userId').val(id);
            $('#change_view_modal').modal('show');
        }
    }
    function checkAndUpdateRoleForm() {
        $('#errorMsg').html('');
        var selectedRole = $('#selectedRole').val().trim();
        var userId = $('#userId').val();
        if(selectedRole == '' || typeof selectedRole === 'undefined'){
            $('#errorMsg').html('');
            $('#errorMsg').html("Role should not be empty");
            return false;
        }
        if(userId == '' || typeof userId === 'undefined'){
            $('#errorMsg').html('');
            $('#errorMsg').html("User Not Found Try Again!");
            return false;
        }
        $.ajax({
            url: "{{ url('/upateUserRole') }}",
            method: 'post',
            dataType:"JSON",
            data: {
                "userId": userId,
                "role": selectedRole,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                // console.log(data);
                if(data.status == 'success') {
                    $('#successMsg').html('');
                    $('#successMsg').html(data.message);
                    setTimeout(() => {
                        $('#change_view_modal').modal('hide');
                        var url = "{{URL::TO('ghgUserList')}}";
                        $("#ghgDiv").html('');
                        $("#ghgDiv").load(url);
                    }, 2000);
                } else if(data.status == 'error') {
                    $('#errorMsg').html('');
                    $('#errorMsg').html(data.message);
                }
            }
        });
    }   

    function deleteUser(userId) {
        if(userId == '' || typeof userId === 'undefined') {
            bootbox.alert("Invalid User Found!");
        }
        bootbox.confirm('Are you sure to delete this user!',
                function(result) {
                console.log('This was logged in the callback: ' + result);
                if(result) {
                    $.ajax({
                        url: "{{ url('/userDelete') }}",
                        method: 'post',
                        dataType:"JSON",
                        data: {
                            "id": userId,
                            "type": '',
                            "isAjax":'yes',
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            console.log(data);
                            if(data.status == 'success') {
                                bootbox.alert(data.message);
                                var url = "{{URL::TO('ghgUserList')}}";
                                $("#ghgDiv").html('');
                                $("#ghgDiv").load(url);
                            } else if(data.status == 'error') {
                                bootbox.alert(data.message);
                            }
                        }
                    });
                }
            });
    } 
    function editUser(encUserId) {
        if(encUserId == '' || typeof encUserId === 'undefined') {
            bootbox.alert("Invalid User Found!");
        }   
        var url = "{{URL::TO('/ghgUserEditScreen')}}/"+encUserId;
        $("#ghgDiv").html('');
        $("#ghgDiv").load(url);
    }  
</script>