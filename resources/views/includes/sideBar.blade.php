<div class="accordion" id="sideBarAccord">
    <div class="questnBx">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button <?= basename($_SERVER["REQUEST_URI"]) == 'ghgEmissionsView' ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#sideBarAccord1" aria-expanded="<?= basename($_SERVER["REQUEST_URI"]) == 'ghgEmissionsView' ? 'true' : 'false' ?>" aria-controls="sideBarAccord1">
                    Scope 1
                </button>
            </h2>
            <div id="sideBarAccord1" class="accordion-collapse collapse <?= (basename($_SERVER["REQUEST_URI"]) == 'ghgEmissionsView') || (basename($_SERVER["REQUEST_URI"]) == 'refrigerantsghgEmissionsView') || (basename($_SERVER["REQUEST_URI"]) == 'mobileghgEmissionsView') ? 'show' : '' ?>" aria-labelledby="headingOne" data-bs-parent="#sideBarAccord">
                <div class="accordion-body">
                    <ul>
                        <li>
                            <a href="ghgEmissionsView" class="sideBtn <?= basename($_SERVER["REQUEST_URI"]) == 'ghgEmissionsView' ? 'active' : '' ?>" id="coneStationary">1.Stationary Combustion</a>
                        </li>
                        <li>
                            <a href="mobileghgEmissionsView" class="sideBtn scopeOne <?= basename($_SERVER["REQUEST_URI"]) == 'mobileghgEmissionsView' ? 'active' : '' ?>" id="coneMobile">2.Mobile Combustion</a>
                        </li>
                        <li>
                            <a href="refrigerantsghgEmissionsView" class="sideBtn scopeOne <?= basename($_SERVER["REQUEST_URI"]) == 'refrigerantsghgEmissionsView' ? 'active' : '' ?>" id="coneRefrigerants">3.Refrigerants</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button <?= basename($_SERVER["REQUEST_URI"]) == 'ElectricityofEvs' ? '' : 'collapsedd' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#sideBarAccord2" aria-expanded="<?= basename($_SERVER["REQUEST_URI"]) == 'ElectricityofEvs' ? 'true' : 'false' ?>" aria-controls="sideBarAccord2">
                    Scope 2
                </button>
            </h2>

            <div id="sideBarAccord2" class="accordion-collapse collapse  <?= basename($_SERVER["REQUEST_URI"]) == 'ElectricityPurchased' ? 'show' : '' ?>" aria-labelledby="headingTwo" data-bs-parent="#sideBarAccord">
                <div class="accordion-body">
                    <ul>
                        <li>
                            <a href="ElectricityPurchased" class="ElectricityPurchased <?= basename($_SERVER["REQUEST_URI"]) == 'ElectricityPurchased' ? 'active' : '' ?>">1.Electricity purchased</a>
                        </li>
                        <li>
                            <a href="ElectricityofHeat" class="ElectricityofHeat <?= basename($_SERVER["REQUEST_URI"]) == 'ElectricityofHeat' ? 'active' : '' ?>">2.Purchase of heat/steam/cold</a>
                        </li>
                        <li>
                            <a href="ElectricityofEvs" class="ElectricityofEvs <?= basename($_SERVER["REQUEST_URI"]) == 'ElectricityofEvs' ? 'active' : '' ?>">3.Electricity for Evs</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sideBarAccord3" aria-expanded="false" aria-controls="sideBarAccord3">
                    Scope 3
                </button>
            </h2>
            <div id="sideBarAccord3" class="accordion-collapse collapse  <?= basename($_SERVER["REQUEST_URI"]) == 'purchaseOfElectricity' ? 'show' : '' ?>" aria-labelledby="headingTwo" data-bs-parent="#sideBarAccord">
                <div class="accordion-body">
                    <ul>
                        <li>
                            <a href="purchasedgoodsandservices" class="<?= basename($_SERVER["REQUEST_URI"]) == 'purchasedgoodsandservices' ? 'active' : '' ?>">1.Purchased Goods & Services</a>
                        </li>
                        <li>
                            <a href="fuelandenergy" class="<?= basename($_SERVER["REQUEST_URI"]) == 'fuelandenergy' ? 'active' : '' ?>">2.Fuel & Energy not in S1 & S2</a>
                        </li>
                        <li>
                            <a href="capitalgoods" class="<?= basename($_SERVER["REQUEST_URI"]) == 'capitalgoods' ? 'active' : '' ?>">3.Capital Goods</a>
                        </li>
                        <li>
                            <a href="waste" class="sideBtn scopeOne <?= basename($_SERVER["REQUEST_URI"]) == 'waste' ? 'active' : '' ?>" id="waste">4.Waste</a>
                        </li>
                        <li>
                            <a href="businesstravel" class="sideBtn scopeOne <?= basename($_SERVER["REQUEST_URI"]) == 'businesstravel' ? 'active' : '' ?>" id="businesstravel">5.Business Travel</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">6.Upstream Leased Assets</a>
                        </li>
                        <li>
                            <a href="employeecommute" class="sideBtn scopeOne <?= basename($_SERVER["REQUEST_URI"]) == 'employeecommute' ? 'active' : '' ?>" id="employeecommute">7.Employee Commute</a>
                        </li>
                        <li>
                            <a href="downstream" class="sideBtn scopeOne <?= basename($_SERVER["REQUEST_URI"]) == 'downstream' ? 'active' : '' ?>">8.Downstream T&D</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">9.Processing of Sold Products</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">10.Use of Sold Products</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">11.End of Life of Sold Products</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">12.Downstream Leased Assets</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">13.Franchises</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">14.Investments</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="userBx ">
        <div class="accordion-item">

            <div id="sideBarAccord1" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#sideBarAccord">
                <div class="accordion-body">
                    <ul>
                        <li>
                            <a href="avascript:void(0)" class="userBtns">
                                All Users
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="sideBtn newuserBtn">Add new user</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>