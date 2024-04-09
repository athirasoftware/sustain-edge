<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="icon"  type="image/x-icon" />
        <meta name="description" content="Nandamrbn Add Remove Input Fields Dynamically using jQuery">
        <meta name="keywords" content="tutorials, programming, coding">
        <meta name="author" content="Nandamrbn">
        <title>RBN - Add Remove Input Fields Dynamically using jQuery</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            .floatRight {
                float: right;
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                var original = document.getElementById('formDiv');
                $('.addNewForm').click(function(){
                    duplicateDivContent(original);
                });
                
                $(".container").on('click', '.btn-primary', function(e) {
                    e.preventDefault();
                    var btnRandomNum = $(this).find('.randomNum').text();
                    // console.log('this is the click '+btnRandomNum);
                });

                // Once remove button is clicked
                $(".container").on('click', '.removeForm', function() {
                    var closeRandomNum = $(this).find('.randomNum').text();
                    var parentDiv = $('#formDiv_'+closeRandomNum);
                    // console.log("closeRandomNum => "+closeRandomNum);
                    parentDiv.remove();
                });
                
            });
            function duplicateDivContent(original, sno) {
                var randNum = Math.floor(Math.random() * 50000);
                var clone = original.cloneNode(true); // "deep" clone
                var formDivId = '';
                clone.id = clone.id+"_" +randNum;
                formDivId = clone.id;
                original.parentNode.appendChild(clone);
                $('#'+formDivId+' input').each(function(){
                    // results.push({ id: this.id, value: this.value  });
                    // console.log(this.id+" <= id , name => "+this.name);
                    $(this).attr('id', function(i , id) { return id + "_" +randNum })
                    $(this).attr('name', function(i , name) { return name + "_" +randNum })
                });
                $('#'+formDivId).find('.randomNum').text(randNum);
                $('#'+formDivId).find('.removeForm').show();

            }
        </script>
    </head>
    <body>
        <div class="container mt-4">
            <h4>Duplicate For Divs With Unique Id's</h2>
            <div class="col-md-8">
                <div id="formHead">
                    <div class="floatRight">
                            <a href="javascript:void(0);" class="addNewForm" title="Add Form" style="text-decoration:none">ADD <i class="fa-solid fa-plus"></i></a>
                    </div>
                    <div class="duplicate" id="duplicateContent">
                        <div class="mt-5" id="formDiv">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title" value = "" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input name="description" id="description" value = ""  class="form-control" required="">
                                </div>
                                <div class="form-group">
                                    <button id="submitBtn" class="btn btn-primary">Submit<span class="randomNum" style="display:none"></span></button>
                                </div>
                            <div class="floatRight">
                                <a href="javascript:void(0);" class="removeForm" id="removeForm" title="Remove Form" style="text-decoration:none; display:none">Remove <i class="fa-solid fa-minus"></i><span class="randomNum" style="display:none"></span></a>
                            </div>
                        </div>
                    <div>
                <div>
                
            <div>
        </div>
    </body>
</html>
