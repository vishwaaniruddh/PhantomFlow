<?php require 'partials/head.php';

$pdo = Connection::make();

$sql1 = "SELECT * FROM mis_component WHERE status = 1";
$query1 = $pdo->prepare($sql1);
$query1->execute();
$result1 = [];
while ($row1 = $query1->fetch(PDO::FETCH_ASSOC)) {
    $name1 = $row1["name"];
    $id1 = $row1["id"];
    $result1[] = ['id' => $name1, 'name' => $name1];
}
$data = json_encode($result1);


$sql2 = "SELECT * FROM mis_subcomponent WHERE status = 1 ORDER BY id DESC";
$query2 = $pdo->prepare($sql2);
$query2->execute();
$result2 = [];
while ($row2 = $query2->fetch(PDO::FETCH_ASSOC)) {
    $model2 = $row2["name"];
    $component_id = $row2["component_id"];
    $id = $row2['id'];
    $result2[] = ['id' => $id, 'fk' => $component_id, 'name' => $model2];
}
$data2 = json_encode($result2);


?>


                                                          


<script type="text/javascript" src="app/views/partials/assets/typeahead.js"></script>
<script>
    function addOptionTags() {
        GroupCount++;
        var sId = 'comp-'+GroupCount;
        var s = $('<select id="'+sId+'" class="form-control comp typeahead col-sm-4" name="comp[]"  required />');
        var s2 = $('<select id="subcomp-'+sId+'" class="form-control subcomp typeahead col-sm-4" name="subcomp[]" onchange="checkComp('+GroupCount+')" required />');
        var docket = $('<input type="text" name="docket_no[]" class="form-control col-sm-4" placeholder="Docket No." required>');
        $("<option value=''> Select comp</option>").appendTo(s);
        $("<option value=''> Select subcomp</option>").appendTo(s2);
    
        
        for(var val of Set1) {
            $("<option />", {value: val.id, text: val.name}).appendTo(s);
        }
            
            s.appendTo(".selectContainer");
            s2.appendTo(".selectContainer");
            docket.appendTo(".selectContainer");
        
        }
</script>

            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="page-body">
                                <div class="card">
                                    <div class="card-block">
                                    <!-- action="process_addMis"                      -->
                                        <form id="misForm" method="POST"> 
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>ATM ID</label>
                                                        <div class="input-group input-group-button">
                                                            <input type="text" name="atmid" id="atmid" class="form-control" placeholder="Atm ID">
                                                        </div>
                                                </div>
                                                    
                                                    
                                                <div class="col-sm-4">
                                                    <label class="label_label">Bank</label>
                                                    <input type="text" name="bank" id="bank" class="form-control">
                                                </div>

                                                <div class="col-sm-4">
                                                    <label>Customer</label>
                                                    <select class="form-control" id="customer" name="customer" required>
                                                            <option value="">Select Customer</option>
                                                            <?php

                                                            $sql = "SELECT DISTINCT customer FROM sites WHERE status = 1";
                                                            $query = $pdo->prepare($sql);
                                                            $query->execute();
                                                            
                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                                $customer = strtoupper($row['customer']);
                                                                echo '<option value="' . $customer . '">' . $customer . '</option>';
                                                            }
                                                            ?>
                                                        </select>

                                                </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <label class="label_label">Zone</label>
                                                        <input class="form-control" type="text" name="zone" id="zone" >
                                                    </div>
                                                    
                                                    <div class="col-sm-2">
                                                        <label class="label_label">City</label>
                                                        <input class="form-control" type="text" name="city" id="city" required>
                                                    </div>
                                                    
                                                    <div class="col-sm-2">
                                                        <label class="label_label">State</label>
                                                        <select name="state" id="state" class="form-control" required>
                                                            <option value="">Select State</option>
                                                            <?php

                                                            $state_sql = "SELECT DISTINCT state FROM sites WHERE status = 1";
                                                            $state_query = $pdo->prepare($state_sql);
                                                            $state_query->execute();
                                                            
                                                            while ($state_sql_result = $state_query->fetch(PDO::FETCH_ASSOC)) {
                                                                $state = $state_sql_result['state'];
                                                                $selected = ($state == $selected_state) ? 'selected' : '';
                                                                echo '<option value="' . $state . '" ' . $selected . '>' . $state . '</option>';
                                                            }
                                                            ?>
                                                        </select>


                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="label_label">Locations</label>
                                                        <input class="form-control" type="text" name="location" id="location" />
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">                            
                                                <div class="col-sm-3">
                                                    <label class="label_label">Engineer</label>
                                                    
                                                    <select class="form-control" name="engineer" id="engineer">
                                                        <option>-- Select --</option>
                                                        <?php

                                                        $eng_sql = "SELECT * FROM vendorUsers WHERE vendorId = :vendorId AND level = 3 AND user_status = 1";
                                                        $eng_query = $pdo->prepare($eng_sql);
                                                        $eng_query->bindParam(':vendorId', $RailTailVendorID, PDO::PARAM_INT);
                                                        $eng_query->execute();

                                                        while ($eng_sql_result = $eng_query->fetch(PDO::FETCH_ASSOC)) {
                                                            $engid = $eng_sql_result['id'];
                                                            $engname = $eng_sql_result['name'];
                                                            echo '<option value="' . $engid . '">' . $engname . '</option>';
                                                        }
                                                        ?>
                                                    </select>

                                                </div>

                                                    
                                                    <div class="col-sm-3">
                                                        <label>Call Type</label>
                                                        <select name="call_type" id="call_type" class="form-control" required>
                                                            <option value="">-- Select --</option>
                                                            <option value="Service">Service</option>
                                                        </select>
                                                    </div>
                                                    

                                                </div>
                                                
                                                <hr>

                        <style>
                        #call_result{
                            margin:30px;
                        }
                        #call_result .card{
                            display: block;
                            background: #e0e0e0;
                            color: white;
                            padding: 15px;    
                        }
                        #call_result label{
                            color:black;
                        }
                        input:focus , select:focus{
                            border: 3px solid red !important
                        }

                        </style>                                      
                                                        
                                                    <div id="call_result">

                                                        
                                                    </div>
                                                        
                                                        
                                                    <div class="row">    
                                                        <div class="col-sm-12">
                                                            <label>Remarks</label>
                                                            <textarea class="form-control" name="remarks"></textarea>
                                                        </div>
                                                            
                                                    </div>                                
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <input type="submit" id="submit" class="btn btn-primary" value="submit">    
                                                        </div>
                                                    </div>
                                        </form>
                                    </div>
                                </div>
                                
                                
                                <div id="show_history"></div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            
            
    <script>
            
            $(document).ready(function(){
                $("#atmid").focus();
            })
            
                $(document).on('change','#call_type',function(){
                    let call_type = $("#call_type").val();
                    console.log(call_type);
                    $("#call_result").html('');
                     if(call_type=='Service'){


                        addOptionTags();


                        let a = `<div class="card row" id="Service_section" >
                                <div class="col-sm-12">
                                    <label>Call Receive From</label>
                                    <select class="form-control" name="call_receive" id="call_receive" reuqired>
                                        <option value="">Select</option>
                                        <option value="Customer / Bank">Customer / Bank</option>
                                        <option value="Internal">Internal</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 selectContainer" style="padding: 15px;"></div>

                                <div class="col-sm-4">
                                    <input type="button" id="add" class="btn btn-primary" onclick="addOptionTags()" value="Add More +">
                                </div>    
                            </div>`;
                        $("#call_result").html(a);  
                        $("#add").click();
                        
                    }
                })
        
        $("#atmid").on('change',function(){
           var atmid = $("#atmid").val();
           
           $.ajax({
            type: "POST",
            url: 'get_atm_data',
            data: 'atmid='+atmid,
            success:function(msg) {
                
                console.log(msg);
                 
                if(msg !=0 ){
                    var obj = JSON.parse(msg);
                    var customer = obj['customer'];
                    var bank = obj['bank'];
                    var location = obj['location'];
                    var city = obj['city'];
                    var state = obj['state'];
                    var region = obj['region'];
                    var bm = obj['bm'];
                    var branch = obj['branch'];
                    var engineer = obj['engineer'];
                    
                    
                    if(!customer){
                        $("#customer").focus();
                    }else{
                        $("#customer").val(customer);               $('#customer').attr('readonly', true);
                    }



                    
                    if(!bank){
                        $("#bank").focus();
                    }else{
                        $("#bank").val(bank);               $('#bank').attr('readonly', true);
                    }
                    
                    if(!engineer){
                        $("#engineer").focus();
                    }else{
                        $("#engineer").val(engineer);               $('#engineer').attr('readonly', true);
                    }
                    
                    
                    if(!location){
                        $("#location").focus();
                    }else{
                        $("#location").val(location);           $('#location').attr('readonly', true);                        
                    }
                    
                    if(!region){
                        $("#zone").focus();
                    }else{
                        $("#zone").val(region);             $('#zone').attr('readonly', true);
                    }
                    
                    if(!state){
                        $("#state").focus();
                    }else{
                        $("#state").val(state);             $('#state').attr('readonly', true);                    
                    }
                    
                    if(!city){
                        $("#city").focus();
                    }else{
                        $("#city").val(city);               $('#city').attr('readonly', true);
                        
                    }
                    
                    if(!branch){
                        $("#branch").focus();
                    }else{
                        $("#branch").val(branch);               $('#branch').attr('readonly', true);
                        
                    }
                    
                    if(!bm){
                        $("#bm").focus();
                    }else{
                        $("#bm").val(bm);               $('#bm').attr('readonly', true);
                        
                    }
                    
                    
                    
                    if(customer && bank && location && region && state && city && branch && bm){
                        $("#call_receive").focus();
                    }
                    
                    $("#call_type").focus();

                
                    
                }
                else{
                    alert('No Info With This ATM');
                   
                }


            }
});



           $.ajax({
            type: "POST",
            url: 'show_history',
            data: 'atmid='+atmid,
            success:function(msg) {
                $("#show_history").html(msg);
            }
           });




           
        });
        
        form.setAttribute( "autocomplete", "off" ); 

    </script>
    
    <script>
        var GroupCount = 0;
        var Set1 = <?php echo $data; ?>;        
        var Set2 = <?php echo $data2; ?>;
        
        function LoadSet2Options(fk, set2Id) { 
            var op = $("#"+set2Id);
            console.log(op)
            op.empty();
            var html = '<option value="">Select SubComponent</option>';
            op.html(html);
            for(var val of Set2) {
                if(val.fk == fk) {
                    $("<option />", {value: val.id, text: val.name}).appendTo(op);
                }
            }
        }
        
        
        function checkComp(key) {
            var comp = $('#comp-'+key).find('option:selected').text(); 
            console.log(comp);
            var subcomp = $('#subcomp-comp-'+key).find('option:selected').text();
            var atmid = $('#atmid').val();
           

            $.ajax({
            type: "POST",
            url: 'add_mis_comp_check',
            data: 'atmid='+atmid+'&component='+comp+'&subcomponent='+subcomp,
            // {atmid:atmid,component:comp,subcomponent:subcomp},
            success:function(msg) {
                console.log('msg = ' + msg );
                if(msg==1){
                    $('#subcomp-comp-'+key).val("");
                    swal("Warning !", "Firstly Close selected subcomponent for this atmid !", "error");
                }
            }
           });
        }
        
        


        $(document).on('change', '.comp', function() {
            console.log('sub')
            LoadSet2Options($(this).val(), "subcomp-"+$(this).attr("id"));
            var str = $(this).attr("id");
            var splitstr = str.split("-");
           // checkComp(splitstr[1]);
        });

        
        
        
        
$('#but_add').click(function(){
  var newel = $('.input-form:last').clone();
  $(newel).insertAfter(".input-form:last");
 });
        
        
    </script>

<script>
    document.getElementById('misForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Create a FormData object from the form
        var formData = new FormData(this);

        // Assuming you're sending the form data via AJAX
        $.ajax({
            url: './process_addMis',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    swal('Success', response.message, 'success');

                } else {
                    swal('Error', response.message, 'error');
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

</script>
<?php  require 'partials/footer.php'; ?>