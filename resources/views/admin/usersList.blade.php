<?php 
    use Illuminate\Support\Facades\Crypt;
    
?>
<div class="row">
    <div class="col-12 sedge-sub-head"> All Users Lists </div>
    <div class="col-12">
        <table id="datatable" class="table" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <td>Name</td>
                    <td>Department</td>
                    <td>Role</td>
                    {{-- <td>Head Quarters</td>
                    <td>Country</td>  --}}
                    <td>Actions</td>
                </tr>
            </thead>
        </table>
    </div>
</div>


<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            // console.log("admin users list");
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('getUsersList') }}",
                    type: "POST",
                    data: function (data) {
                        data.search = $('input[type="search"]').val();
                    }
                },
                order: ['1', 'DESC'],
                //pageLength: 10,
                lengthMenu: [2, 5, 10, 20, 50, 100, 200, 500],
                searching: true,
               /*  columnDefs: [
                    {
                        target: 5,
                        visible: false
                    }
                ], */
                aoColumns: [
                    {
                        data: 'full_name',
                    },
                    {
                        data: 'department',
                    },
                   /*  {
                        data: 'name_of_org',
                    },
                    {
                        data: 'industry',
                    },
                    {
                        data: 'head_quarters',
                    },
                    {
                        data: 'country',
                    }, */
                    {
                        data: 'role',
                    },
                    {
                        data: 'id',
                        width: "20%",
                        render: function(data, type, row) {
                            // console.log(row.id);
                            var urlUserEdit = "{{URL::TO('userEdit')}}?id="+encodeURI(row.encryptedId)+"&type=edit";
                            var urlUserDelete = "{{URL::TO('userDelete')}}?id="+encodeURI(row.encryptedId)+"&type=delete";
							
                            var editRandomNum = Math.floor((Math.random() * 10) + 1);
                            var deleteRandomNum = Math.floor((Math.random() * 10) + 1);
                           return '<a href="javascript:void(0)" onclick="userAction('+editRandomNum+')" title="Edit"> <i class="fa fa-pencil" style="font-size:18px;color:blue"></i></a><input type="hidden" id="'+editRandomNum+'" value="'+urlUserEdit+'" class="edit"> <a href="javascript:void(0)" onclick="userAction('+deleteRandomNum+')" title="Delete"> <i class="fa fa-trash" style="font-size:18px;color:red"></i></a><input class="delete" type="hidden" id="'+deleteRandomNum+'" value="'+urlUserDelete+'">'; //you can add your view route here
                        }
                    }
                ]
            });
        });
        function userAction(id) {
            var url = $('#'+id).val();
            var className = $('#'+id).attr('class');
            if(className == 'delete') {
                window.location.replace(url);
            } else if(className == 'edit') {
                $("#adminDiv").html();
                $("#adminDiv").load(url);
            }
        }
        
    </script>
