<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Tabs - Vertical Tabs functionality</title>
  <script src="https://code.jquery.com/jquery-2.2.3.js"></script>
  <link rel="stylesheet" href="jquery/jquery-ui.css">
  <script src="jquery/jquery-ui.js"></script>

  <script>
  $(function() {
    $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  });
  </script>
  <style>
      
   label {display: block; vertical-align: top;padding-top: 10px;padding-bottom: 5px;}   
   textarea {
	width: 400px;
	height: 100px;
        resize: none;
}   
      
input[type=text] {
    padding: 0;
    height: 30px;
width: 400px;
}

  .ui-tabs-vertical { width: 55em; }
  .ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 13em; }
  .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
  .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
  .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }
  .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 39em;}
  </style>
</head>
<body>
 
<div id="tabs">
  <ul>

    
    
    
<li><a href="#tabs-1">Report Details</a></li>
<li><a href="#tabs-2">Building Details</a></li>
<li><a href="#tabs-3">Strata Type Selection</a></li>
<li><a href="#tabs-4">Property Statistics</a></li>
<li><a href="#tabs-5">Construction Information</a></li>
<li><a href="#tabs-6">Component Selection</a></li>
<li><a href="#tabs-7">Component Confirmation</a></li>
<li><a href="#tabs-8">Component Details</a></li>


  </ul>
  <div id="tabs-1">
      <label>Strata Number:</label>
      <span><input type="text" id="inputStrataNumber"></span>
      <br>
      <label>Client: </label>
      <span><select id="selectClient"></select></span>
      <div id="selectInspectorContainer">
          <label>Inspectors:</label>
          <span><select class="selectInspector"></select></span>
          <input type="button" id="btnAddInspector" value="Add Inspector">
      </div>
      <br>
      <div id="dateOfInspectionContainer">
          <label>Date of Inspection:</label>
          <span><input type="date" class="inputDateOfInspect"></span>
          <input type="button" id="btnAddDate" value="Add Date">
      </div>
<br>
      <label>Effective Date of Report:</label>
      <span><input type="date" id="inputEffectiveDate"></span>
<br>
      <label>Strata Plans:</label>
      <span><textarea id="inputStrataPlans"></textarea></span>
      <br><label>Building Plans, Schedules and Details:</label>
      <span><textarea id="inputPlansScheduleDetails"></textarea></span>
     <br> <label>Site Plans:</label>
      <span><textarea id="inputSitePlans"></textarea></span>
     <br> <label>Material Given to Inspectors:</label>
      <span><textarea id="inputMaterialGiven"></textarea></span>
      <br>
      <input type="button" value="Continue" class="saveContinueButton" id="btnReportDetailsFinish">
      
  </div>
  <div id="tabs-2">
    <h2>Content heading 2</h2>
    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
  </div>
  <div id="tabs-3">
    <h2>Content heading 3</h2>
    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
  </div>
</div>
 
 
</body>
</html>
    <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

