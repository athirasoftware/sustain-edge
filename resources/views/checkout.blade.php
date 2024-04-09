@include('includes.header') 

<div id="pageWrapper" class="checkoutPage">

    <section id="checkout">
        <div class="container">
            <div class="titleBx">
                <div class="icon">
                    <img src="{{URL::To('assets/images/logo.png')}}" alt="">
                </div>
                <h2>Complete Your Payment</h2>
            </div>
            <div class="cmnBx">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="box">
                            <div class="sbTxt">You have selected GHG Emissions Calculator |
                                <a href="javascript:void(0)" class="redbtn"> Add more</a>
                            </div>
                            <div class="price">₹ 1000</div>
                            <ul class="paymentBx">
                                <li>
                                    <span>GHG Emissions Calculator</span>
                                    <span>₹ 1,000 </span>
                                </li>
                                <li>
                                    <span>Subtotal</span>
                                    <span>₹ 1,000 </span>
                                </li>
                                <li>
                                    <span>GST at 18%</span>
                                    <span>₹ 180</span>
                                </li>
                                <li>
                                    <span>Total amount</span>
                                    <span>₹ 1180</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="box1">
                            <div class="sbTxt">Payment details </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Businees Email</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-lg-12">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-lg-12">
                                    <label>Card Number</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label>Expiry Date</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label>CVV</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="hoveranim cmnbtn"><span>Make Payment</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@include('includes.footer')