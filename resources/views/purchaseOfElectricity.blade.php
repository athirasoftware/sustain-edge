@include('includes.header')
<div id="pageWrapper" class="cmnPage sidebarPage">

    <section id="scope">
        <div class="container">
            <div class="titleBx">
                <div class="icon">
                    <img src="assets/images/logo.png" alt="">
                </div>
                <h2>GHG Emissions Calculator</h2>
            </div>
            <div class="cmnBx">
                <div class="flx">
                    <div class="lefBx">
                        <div class="name">Hello john,</div>
                        <div class="cmpny">ABC Company</div>
                        <a href="javascript:void(0)" class="links">Logout</a>
                        <div class="sideBox">
                            <?php //include './includes/sideBar.php'?>
                            @include('includes.sideBar')
                        </div>
                    </div>
                    <div class="rtsec">
                    <div class="topBox">
                            <div class="accordHead">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button qusetnBtn" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dashBoarAccord1" aria-expanded="true"
                                        aria-controls="dashBoarAccord1">
                                        Qustionnaire
                                    </button>
                                </h2>
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dashBoarAccord2" aria-expanded="false"
                                        aria-controls="dashBoarAccord2">
                                        Report
                                    </button>
                                </h2>

                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed userBtn" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dashBoarAccord3" aria-expanded="false"
                                        aria-controls="dashBoarAccord3">
                                        user
                                    </button>
                                </h2>
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dashBoarAccord4" aria-expanded="false"
                                        aria-controls="dashBoarAccord4">
                                        settings
                                    </button>
                                </h2>
                            </div>

                        </div>
                        <div class="accordion" id="dashBoarAccord">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dashBoarAccord1" aria-expanded="true"
                                        aria-controls="dashBoarAccord1">
                                        Qustionnaire
                                    </button>
                                </h2>
                                <div id="dashBoarAccord1" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#dashBoarAccord">
                                    <div class="accordion-body ">
                                        
                                        <div class="cmnBx">
                                            <div class="mainTitle">1. Purchased Goods & Services</div>
                                            <form action="javascript:void(0)">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="">Purchased Goods & Services *</label>
                                                        <select name="" id="" class="form-control">
                                                            <option value="">select</option> 
                                                        </select>
                                                        <div class="label">select the Purchased Goods & Services to add them below</div>
                                                    </div>
                                                </div>
                                            </form>
                                           
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dashBoarAccord2" aria-expanded="false"
                                        aria-controls="dashBoarAccord2">
                                        Report
                                    </button>
                                </h2>
                                <div id="dashBoarAccord2" class="accordion-collapse collapse"
                                    aria-labelledby="headingTwo" data-bs-parent="#dashBoarAccord">
                                    <div class="accordion-body">

                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dashBoarAccord3" aria-expanded="false"
                                        aria-controls="dashBoarAccord3">
                                        User
                                    </button>
                                </h2>
                                <div id="dashBoarAccord3" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#dashBoarAccord">
                                    <div class="accordion-body">
                                        <div class="  cmnBx userList">
                                            <div class="mainTitle">List of all users in your organisation</div>
                                            <table>
                                                <tr>
                                                    <td>Vijay</td>
                                                    <td>employee <a href="javascript:void(0)">change Role</a></td>
                                                    <td>Authenticated <a href="javascript:void(0)">de-authenticate</a>
                                                        <a href="javascript:void(0)">delete user</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Vijay</td>
                                                    <td>employee <a href="javascript:void(0)">change Role</a></td>
                                                    <td>Authenticated <a href="javascript:void(0)">de-authenticate</a>
                                                        <a href="javascript:void(0)">delete user</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Vijay</td>
                                                    <td>employee <a href="javascript:void(0)">change Role</a></td>
                                                    <td>Authenticated <a href="javascript:void(0)">de-authenticate</a>
                                                        <a href="javascript:void(0)">delete user</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Vijay</td>
                                                    <td>employee <a href="javascript:void(0)">change Role</a></td>
                                                    <td>Authenticated <a href="javascript:void(0)">de-authenticate</a>
                                                        <a href="javascript:void(0)">delete user</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Vijay</td>
                                                    <td>employee <a href="javascript:void(0)">change Role</a></td>
                                                    <td>Authenticated <a href="javascript:void(0)">de-authenticate</a>
                                                        <a href="javascript:void(0)">delete user</a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="cmnBx mt-4 newUser">
                                            <div class="mainTitle">Add a new user to your organisation</div>
                                            <div class="row  ">

                                                <div class="col-lg-6">
                                                    <label for="">Name</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="">Role</label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">select</option> 
                                                    </select>
                                                </div>

                                                <div class="col-lg-12">
                                                    <label for="">Department</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="rtsertBxc">
                                                        <div class="item">
                                                            <a href="javascript:void(0)"
                                                                class="cmnbtn hoveranim"><span>Edit</span></a>
                                                        </div>
                                                        <div class="item">
                                                            <a href="javascript:void(0)"
                                                                class="cmnbtn hoveranim"><span>Remove</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dashBoarAccord4" aria-expanded="false"
                                        aria-controls="dashBoarAccord4">
                                        Settings
                                    </button>
                                </h2>
                                <div id="dashBoarAccord4" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#dashBoarAccord">
                                    <div class="accordion-body">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
    
    </script>
</div>
@include('includes.footer')