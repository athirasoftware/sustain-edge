<div class="mainTitle" style="text-align:center;font-size: 30px;">Scope1</div>

     @if(isset($stationary) && count($stationary) > 0)
     <div class="cmnBx userList" style="marign-bottom:10px;">   
                    <div class="mainTitle">1.Stationary Combution</div>
                        @foreach($stationary as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->fuel_particular) && $item->fuel_particular != '')
                                        {{$item->fuel_particular}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->total_emission) && $item->total_emission != '')
                                        {{ $item->total_emission }} {{ $item->standard }}
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
</div>
<br>
                    @endif
                    @if(isset($mobilereports) && count($mobilereports) > 0)
                    <div class="cmnBx userList">     
                    <div class="mainTitle">2.Mobile Combution</div>
                        @foreach($mobilereports as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->fuel_particular) && $item->fuel_particular != '')
                                        {{$item->fuel_particular}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->total_emission) && $item->total_emission != '')
                                        {{ $item->total_emission }} {{ $item->standard }}
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
                        </div>
                        <br>
                    @endif
               
                    @if(isset($refrigerants) && count($refrigerants) > 0)
                    <div class="cmnBx userList">     
                    <div class="mainTitle">3.Refrigerants</div>
                        @foreach($refrigerants as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->fuel_particular) && $item->fuel_particular != '')
                                        {{$item->fuel_particular}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->total_emission) && $item->total_emission != '')
                                        {{ $item->total_emission }} {{ $item->standard }}
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
                        </div>
                        <br>
                    @endif
            
        </div>
    </div>
</div>
</div>

<div class="mainTitle" style="text-align:center;font-size: 30px;">Scope2</div>

     @if(isset($purchaseofElectricity) && count($purchaseofElectricity) > 0)
     <div class="cmnBx userList" style="marign-bottom:10px;">   
                    <div class="mainTitle">1.Purchase of Electricity</div>
                        @foreach($purchaseofElectricity as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->fuel_particular) && $item->fuel_particular != '')
                                        {{$item->fuel_particular}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->total_emission) && $item->total_emission != '')
                                        {{ $item->total_emission }} {{ $item->standard }}
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
</div>
     @endif
         </div>
    </div>
</div>
</div>

<div class="mainTitle" style="text-align:center;font-size: 30px;">Scope3</div>

     @if(isset($purchaseGoodsAndService) && count($purchaseGoodsAndService) > 0)
     <div class="cmnBx userList" style="marign-bottom:10px;">   
                    <div class="mainTitle">1.Purchased Goods & Services</div>
                        @foreach($purchaseGoodsAndService as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->purchase_item) && $item->purchase_item != '')
                                        {{$item->purchase_item}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->pur_total_emissions) && $item->pur_total_emissions != '')
                                        {{ $item->pur_total_emissions }}
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
</div>
<br>
                    @endif
                    @if(isset($CapitalGoods) && count($CapitalGoods) > 0)
                    <div class="cmnBx userList">     
                    <div class="mainTitle">2.Capital Goods</div>
                        @foreach($CapitalGoods as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                           @if(isset($item->capital_goods_item) && $item->capital_goods_item != '')
                                        {{$item->capital_goods_item}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->cap_total_emissions) && $item->cap_total_emissions != '')
                                        {{ $item->cap_total_emissions }} 
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
                        </div>
                        <br>
                    @endif
               
                    @if(isset($WasteManagement) && count($WasteManagement) > 0)
                    <div class="cmnBx userList">     
                    <div class="mainTitle">3.Waste</div>
                        @foreach($WasteManagement as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->wa_waste_type) && $item->wa_waste_type != '')
                                        {{$item->wa_waste_type}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->wa_total_emissions) && $item->wa_total_emissions != '')
                                        {{ $item->wa_total_emissions }} 
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
                        </div>
                        <br>
                    @endif

                    @if(isset($BusinessTravel) && count($BusinessTravel) > 0)
                    <div class="cmnBx userList">     
                    <div class="mainTitle">4.BusinessTravel</div>
                        @foreach($BusinessTravel as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->bu_particulars) && $item->bu_particulars != '')
                                        {{$item->bu_particulars}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->bu_total_emissions) && $item->bu_total_emissions != '')
                                        {{ $item->bu_total_emissions }} 
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
                        </div>
                        <br>
                    @endif

                    @if(isset($EmployeeCommute) && count($EmployeeCommute) > 0)
                    <div class="cmnBx userList">     
                    <div class="mainTitle">7.EmployeeCommute</div>
                        @foreach($EmployeeCommute as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->ec_particulars) && $item->ec_waste_type != '')
                                        {{$item->ec_waste_type}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->ec_total_emissions) && $item->ec_total_emissions != '')
                                        {{ $item->ec_total_emissions }} 
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
                        </div>
                        <br>
                    @endif


                    @if(isset($DownStream) && count($DownStream) > 0)
                    <div class="cmnBx userList">     
                    <div class="mainTitle">8.Downstream T&D</div>
                        @foreach($DownStream as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->ds_particulars) && $item->ds_waste_type != '')
                                        {{$item->ds_waste_type}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->ds_total_emissions) && $item->ds_total_emissions != '')
                                        {{ $item->ds_total_emissions }} 
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
                        </div>
                        <br>
                    @endif


                    <!-- @if(isset($TransportMode) && count($TransportMode) > 0)
                    <div class="cmnBx userList">     
                    <div class="mainTitle">9.TransportMode</div>
                        @foreach($TransportMode as $item)
                           <div><label style="font-weight:bold;">Total Emission for 
                                    @if(isset($item->motm_transport_mode) && $item->motm_transport_mode != '')
                                        {{$item->motm_transport_mode}}
                                   
                                    @endif
                                    </label>
                                    <br><span>@if(isset($item->downStream) && $item->downStream != '')
                                        {{ $item->downStream }} 
                                            
                                    @else 
                                        N/A
                                    @endif</span>
                              </div>
                                
                        @endforeach
                        </div>
                        <br>
                    @endif -->
            
        </div>
    </div>
</div>
</div>
