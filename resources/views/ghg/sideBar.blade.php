<div class="accordion" id="sideBarAccord">
    <div class="questnBx" id="quetionnaireSideBarDiv" style="display:none">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sideBarAccord1" aria-expanded="true" aria-controls="sideBarAccord1">
                    Scope 1
                </button>
            </h2>
            <div id="sideBarAccord1" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#sideBarAccord">
                <div class="accordion-body">
                    <ul>
                        <li>
                            <a href="javascript:void(0)" class="sideBtn active" id="coneStationary">1.Stationary  Combustion</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="sideBtn scopeOne"  id="coneMobile">2.Mobile Combustion</a>
                        </li>
                        <li>
                            <a href="javascript:void(0) " class="sideBtn1 scopeOne"  id="coneRefrigerants">3.Refrigerants</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sideBarAccord2" aria-expanded="false" aria-controls="sideBarAccord2">
                    Scope 2
                </button>
            </h2>
            <div id="sideBarAccord2"
                class="accordion-collapse collapse  <?= basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'purchaseOfElectricty' ? 'show' : '' ?>"
                aria-labelledby="headingTwo" data-bs-parent="#sideBarAccord">
                <div class="accordion-body">
                    <ul>
                        <li>
                            <a href="purchaseElectricty"
                                class="<?= basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'purchaseElectricty' ? 'active' : '' ?>">1.Purchase
                                of Electricity</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sideBarAccord3" aria-expanded="false" aria-controls="sideBarAccord3">
                    Scope 3
                </button>
            </h2>
            <div id="sideBarAccord3" class="accordion-collapse collapse  <?= basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'purchaseOfElectricity' ? 'show' : '' ?>" aria-labelledby="headingTwo"
                data-bs-parent="#sideBarAccord">
                <div class="accordion-body">
                    <ul>
                        <li>
                            <a href="purchaseOfElectricity.php" class=" <?= basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'purchaseOfElectricity' ? 'active' : '' ?>" >1.Purchased Goods & Services</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">2.Fuel & Energy not in S1 & S2</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">3.Capital Goods</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">4.Waste</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">5.Business Travel</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">6.Upstream Leased Assets</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">7.Employee Commute</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">8.Downstream T&D</a>
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
    <div class="userBx" id="userSideBarDiv" style="display:none">
        <div id="sideBarAccord1">
            <ul>
                <li> <a  href="javascript:void(0)" class="usersSubList" id="allUsersBtn" onclick="loadGHGViewBySubDivId(this)"> All Users </a> </li>
                <li> <a  href="javascript:void(0)" class="usersSubList" id="addNewUsersBtn" onclick="loadGHGViewBySubDivId(this)">Add new user</a> </li>
            </ul>
        </div>
    </div>
</div>