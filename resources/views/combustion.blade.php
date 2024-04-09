@include('includes.header')
<div id="pageWrapper" class="planPage">

    <section id="plans">
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
                    </div>
                    <div class="rtsec">
                        <div class="itemFx">
                            <a href="javascript:void(0)" class="lnBtn">Choose</a>
                            <a href="javascript:void(0)" class="lnBtn">Report</a>
                            <a href="javascript:void(0)" class="lnBtn">Users</a>
                            <a href="javascript:void(0)" class="lnBtn">Settings</a>
                        </div>
                    </div>
                </div>

                <div class="sideBx">
                    <div class="ledftBox">
                    @include('includes.sideBar')
                    </div>
                </div>

                 

            </div>
        </div>
    </section>

</div>

@include('includes.footer')