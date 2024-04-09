@include('includes.header')
<div id="pageWrapper" class="loginPage">

    <section id="login">
        <div class="container">
            <h2 class="text-center">Welcome to SustainEDGE Sustainability Tool</h2>
            <div class="loginBox">
                <div class="logoBx">
                    <img src="assets/images/logo.png" alt="">
                </div>
                <div class="title">Login</div>
                <form action="javascript:void(0)">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="text" class="form-control" placeholder="Business Email">
                        </div>
                        <div class="col-lg-12">
                            <input type="text" class="form-control" placeholder="Password">
                            <div class="label">
                                <a href="javscript:void(0)" class="link">Forgot Password</a>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="flx">
                                <div class="item">
                                    <a href="javascript:void(0)" class="cmnbtn hoveranim">
                                        <span>Login</span>
                                    </a>
                                </div>
                                <div class="item">
                                    <a href="javascript:void(0)" class="cmnbtn hoveranim">
                                        <span>Register</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>       
        
        </div>
    </section>

</div>
@include('includes.footer')