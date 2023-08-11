<?php 
   class MisController{
   
       public function index(){
           return view('');
       }
       public function addMis(){
           return view('add_mis');
       }
       public function getAtmData(){
   
           $atmid = $_REQUEST['atmid'];
           $pdo = Connection::make();
   
               if($atmid){
                       $sql = $pdo->prepare("select * from sites where atmid='".$atmid."' and status=1");
                       $sql->execute();
                       
                       if($sql_result = $sql->fetch(PDO::FETCH_ASSOC)){
                           $customer = strtoupper($sql_result['customer']);
                           $bank = $sql_result['bank'];
                           $location = $sql_result['address'];
                           $state = $sql_result['state'];
                           $region = $sql_result['zone'];  
                           $city = $sql_result['city'];
                           
                           $data = ['customer'=>$customer,'bank'=>$bank,'location'=>$location,'city'=>$city,
                           'state'=>$state,'region'=>$region] ; 
                       
                               if($data){
                                   echo json_encode($data);    
                               }else{
                                   echo 0;
                               }
                   }
               }else{
                   echo 0; 
               }
   
   
       }
       public function add_mis_comp_check(){
           $atm_id = $_POST['atmid'];
           $component = $_POST['component'];
           $subcomponent = $_POST['subcomponent'];
           $pdo = Connection::make();
   
   
           $status = 0;
           $_sql="select status from mis_details where atmid='".$atm_id."' and component='".$component."' and subcomponent='".$subcomponent."' order by id desc";
           
           $table=$pdo->prepare($_sql);
           $table->execute();
           
           while($row = $table->fetch(PDO::FETCH_ASSOC)){
   
                   if($row['status']!='close'){
                       $status = 1;
                   }
           
           }
           
           echo $status;
   
       }
       public function process_addMis(){
   
           $pdo = Connection::make();
           $userid = $_SESSION['SERVICE_userid'];
           $datetime = date('Y-m-d H:i:s');
                                       
   
           $status ='open';                      
           $created_by = $userid;
           $created_at = $datetime;
           $atmid = $_POST['atmid'];
           $bank = $_POST['bank'];
           $customer = $_POST['customer'];
           $zone = $_POST['zone'];
           $city = $_POST['city'];
           $state = $_POST['state'];
           $location = $_POST['location'];
           $call_receive = $_POST['call_receive'];
           $remarks = htmlspecialchars($_POST['remarks']);
           $amount = 'NULL';
           $comp = $_POST['comp'];
           $subcomp = $_POST['subcomp'];
           $docket_no = $_POST['docket_no'];
           $count = count($comp);
           $call_type = $_REQUEST['call_type'];
           $engineer_user_id = $_REQUEST['engineer'];
           $serviceExecutive = $_SESSION['SERVICE_username'];
   
           $statement = "insert into mis(atmid,bank,customer,zone,city,state,location,call_receive_from,remarks,status,created_by,created_at,call_type,serviceExecutive) 
           values('".$atmid."','".$bank."','".$customer."','".$zone."','".$city."','".$state."','".$location."','".$call_receive."','".$remarks."','open',
           '".$created_by."','".$created_at."','".$call_type."','".$serviceExecutive."')";
   
           $statement = $pdo->prepare($statement);
           $statement->execute();
   
           if($statement->execute()){
               $mis_id = $pdo->lastInsertId();
                   for($i=0;$i<$count;$i++){
                       $last_sql = $pdo->prepare("select id from mis_details order by id desc");
                       
                       if($last_sql->execute()){
                           $last = $pdo->lastInsertId();
                   
                           $ticket_id =  mb_substr(date('M'), 0, 1).date('Y') .date('m')  . date('d') . time(); ;
                           
                           $com = $comp[$i];
                           
                           $subcomp_sql=$pdo->prepare("select * from mis_subcomponent where id='".$subcomp[$i]."'");
                           $subcomp_sql->execute();
                           $subcomp_sql_result = $subcomp_sql->fetch(PDO::FETCH_ASSOC);
                           $subcom = $subcomp_sql_result['name'];
                           
                           
                           $detai_statement = "insert into mis_details(mis_id,atmid,component,subcomponent,engineer,docket_no,status,created_at,ticket_id,amount,
                           zone,call_type,case_type) 
                           values('".$mis_id."','".$atmid."','".$com."','".$subcom."','".$engineer_user_id."','".$docket_no[$i]."','".$status."','".$created_at."','".$ticket_id."','".$amount."','".$zone."','Service','".$call_receive."')" ;
                           $detai_statement = $pdo->prepare($detai_statement);
                       
                           if($detai_statement->execute()){
   
                           }       
                       }
                   }    
   
                   $response = [
                       'status' => 'success',
                       'message' => 'Call Created Successfully .',
                       'mis_id' => $mis_id
                   ];
   
               } else {
           $response = [
               'status' => 'error',
               'message' => 'Failed to create call.'
           ];
           }
       header('Content-Type: application/json');
       echo json_encode($response);
   
       }
   
       public function getHistory(){
            $atmid = $_POST['atmid'];
            $pdo = Connection::make();
                    
            // Fetch data using PDO
            $sql = "SELECT * FROM mis_details WHERE atmid = :atmid ORDER BY id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':atmid', $atmid, PDO::PARAM_STR);
            $stmt->execute();
            $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $i = 1;

            // Loop through the results and display them in a table
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Sn</th>';
            echo '<th>Ticket ID</th>';
            echo '<th>Component</th>';
            echo '<th>Sub Component</th>';
            echo '<th>Call Receive From</th>';
            echo '<th>Remarks</th>';
            echo '<th>Status</th>';
            echo '<th>Created By</th>';
            echo '<th>Date</th>';
            echo '<th>Closing Date</th>';
            echo '<th>Attachment 1</th>';
            echo '<th>Attachment 2</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($resultSet as $sql_result) {
                $mis_id = $sql_result['id'];

                // Fetch related data using PDO
                $sql1 = "SELECT * FROM mis WHERE id = :mis_id";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':mis_id', $sql_result['mis_id'], PDO::PARAM_INT);
                $stmt1->execute();
                $sql1_result = $stmt1->fetch(PDO::FETCH_ASSOC);

                $created_by = $sql1_result['created_by'];
                $created_at = $sql1_result['created_at'];

                // Fetch user data using PDO
                $user_sql = "SELECT * FROM vendorUsers WHERE id = :created_by";
                $stmt_user = $pdo->prepare($user_sql);
                $stmt_user->bindParam(':created_by', $created_by, PDO::PARAM_INT);
                $stmt_user->execute();
                $user_sql_result = $stmt_user->fetch(PDO::FETCH_ASSOC);
                $created_by = $user_sql_result['name'];

                // Fetch history data using PDO
                $his_sql = "SELECT * FROM mis_history WHERE mis_id = :mis_id AND type = 'close' ORDER BY id DESC";
                $stmt_his = $pdo->prepare($his_sql);
                $stmt_his->bindParam(':mis_id', $sql_result['id'], PDO::PARAM_INT);
                $stmt_his->execute();
                $his_sql_result = $stmt_his->fetch(PDO::FETCH_ASSOC);
                $close_time = $his_sql_result['created_at'];
                $attachment = $his_sql_result['attachment'];
                $attachment2 = $his_sql_result['attachment2'];

                // Display table rows
                echo '<tr>';
                echo '<td>' . $i . '</td>';
                echo '<td><a target="_blank" href="mis_details.php?id=' . $mis_id . '">' . $sql_result['ticket_id'] . '</a></td>';
                echo '<td>' . $sql_result['component'] . '</td>';
                echo '<td>' . $sql_result['subcomponent'] . '</td>';
                echo '<td>' . $sql1_result['call_receive_from'] . '</td>';
                echo '<td>' . $sql1_result['remarks'] . '</td>';
                echo '<td>' . $sql_result['status'] . '</td>';
                echo '<td>' . $created_by . '</td>';
                echo '<td>' . $created_at . '</td>';
                echo '<td>' . $close_time . '</td>';
                echo '<td>';
                if ($attachment) {
                    echo '<a href="' . $attachment . '" class="btn btn-success" target="_blank">View Attachment 1</a>';
                }
                echo '</td>';
                echo '<td>';
                if ($attachment2) {
                    echo '<a href="' . $attachment2 . '" class="btn btn-success" target="_blank">View Attachment 2</a>';
                }
                echo '</td>';
                echo '</tr>';

                $i++;
            }

            echo '</tbody>';
            echo '</table>';


       }

       public function viewMis(){

        $data = ['name'=>'Aniruddh'];
        return view('viewMis',compact('data'));
       }

       public function getMisRecords(){
   
        ob_start();

            $pdo = Connection::make();
   


            $call_type = $_REQUEST['call_type'];
            $call_receive = $_REQUEST['call_receive'];
            

            $statement = "select a.remarks,a.id,a.bank,a.customer,a.location,a.zone,a.state,a.city,a.branch,a.created_by,a.bm,b.id,b.mis_id,b.atmid,
            b.component,b.subcomponent,b.engineer,b.docket_no,b.status,b.created_at,b.ticket_id,b.close_date,b.call_type,b.case_type ,
            
            (SELECT CONCAT(name) from vendorUsers WHERE id= b.engineer) AS eng_name,
            (SELECT CONCAT(contact) from vendorUsers WHERE id= b.engineer) AS eng_contact,
            
            IF(b.footage_date = '0000-00-00 00:00:00', '', DATE_FORMAT(b.footage_date, '%Y-%m-%d')) AS footage_date,b.fromtime,b.totime,
            (SELECT name from vendorUsers WHERE id= a.created_by) AS createdBy,
            cc.type,cc.schedule_date,cc.material,cc.material_condition,cc.courier_agency,cc.pod,cc.serial_number,cc.dispatch_date,cc.status_remark,
            cc.material_req_remark,
            cc.material_dispatch_remark,cc.close_remark,cc.last_action_by,cc.created_date
            
            from mis a
                INNER JOIN mis_details b ON b.mis_id = a.id 
                LEFT JOIN (select c.mis_id as cmisid,c.type,c.schedule_date,c.material,c.material_condition,c.courier_agency,c.pod,c.serial_number,c.dispatch_date,c.remark as status_remark,
                IF(c.type='material_requirement',c.remark,'') as material_req_remark,
                IF(c.type='material_dispatch',c.remark,'') as material_dispatch_remark,
                IF(c.type='close',c.remark,'') as close_remark,
                c.created_by as last_action_by,
                IF(c.type <> 'Open' , c.created_at , '' ) as created_date
                
                
                from mis_history c group by c.mis_id
                
                ) AS cc  ON cc.cmisid = a.id 
                
                where 1 and 
            b.mis_id = a.id 
            ";
            
            
            
            $sqlappCount = "select count(1) as total 
            from mis a
                INNER JOIN mis_details b ON b.mis_id = a.id 
                LEFT JOIN (select c.mis_id as cmisid,c.type,c.schedule_date,c.material,c.material_condition,c.courier_agency,c.pod,c.serial_number,c.dispatch_date,c.remark as status_remark,
                IF(c.type='material_requirement',c.remark,'') as material_req_remark,
                IF(c.type='material_dispatch',c.remark,'') as material_dispatch_remark,
                IF(c.type='close',c.remark,'') as close_remark,
                c.created_by as last_action_by,
                c.created_at as created_date
                from mis_history c  group by c.mis_id
                ) AS cc  ON cc.cmisid = a.id 
                
                where 1 and
            b.mis_id = a.id 
            ";
            

            if (isset($_REQUEST['atmid']) && $_REQUEST['atmid'] != '') {
                $statement .= " and b.atmid = '" . $_REQUEST['atmid'] . "'";
                $sqlappCount.= " and b.atmid = '" . $_REQUEST['atmid'] . "'";
            }
            
            if (isset($_REQUEST['fromdt']) && $_REQUEST['fromdt'] != '' && isset($_REQUEST['todt']) && $_REQUEST['todt'] != '') {

                $date1 = $_REQUEST['fromdt'];
                $date2 = $_REQUEST['todt'];
                
                if(count($_REQUEST['status'])>0){
                    if ($_REQUEST['status'][0]=='close' && count($_REQUEST['status']) == 1 ) {
                        $statement .= " and CAST(b.close_date AS DATE) >= '" . $date1 . "' and CAST(b.close_date AS DATE) <= '" . $date2 . "'";
                        $sqlappCount .= " and CAST(b.close_date AS DATE) >= '" . $date1 . "' and CAST(b.close_date AS DATE) <= '" . $date2 . "'";
                    }
                    else {
                        $statement .= " and CAST(b.created_at AS DATE) >= '" . $date1 . "' and CAST(b.created_at AS DATE) <= '" . $date2 . "'";
                        $sqlappCount .= " and CAST(b.created_at AS DATE) >= '" . $date1 . "' and CAST(b.created_at AS DATE) <= '" . $date2 . "'";
                    }
                }
                else {
                    $statement .= " and CAST(b.created_at AS DATE) >= '" . $date1 . "' and CAST(b.created_at AS DATE) <= '" . $date2 . "'";
                    $sqlappCount .= " and CAST(b.created_at AS DATE) >= '" . $date1 . "' and CAST(b.created_at AS DATE) <= '" . $date2 . "'";
                }
               
            }
            
            

            if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {

                $status = json_encode($_REQUEST['status']);
                $status = str_replace(array('[', ']', '"'), '', $status);
                $arr_status = explode(',', $status);
                $status = "'" . implode("', '", $arr_status) . "'";
                $statement .= " and b.status in($status)";
                $sqlappCount.= " and b.status in($status)";
            } 
            
            else {
                $statement .= " and b.status in('open','permission_require','dispatch','material_requirement','material_in_process','schedule','material_available_i','material_dispatch','cancelled','not_available','available','close','MRS','fund_required','service_center')";
                $sqlappCount .= " and b.status in('open','permission_require','dispatch','material_requirement','material_in_process','schedule','material_available_i','material_dispatch','cancelled','not_available','available','close','MRS','fund_required','service_center')";
            }


            
            if (isset($_REQUEST['call_receive']) && $_REQUEST['call_receive'] != '') {
                $statement .= " and b.case_type = '".$call_receive."'";
                $sqlappCount .= " and b.case_type = '".$call_receive."'";
            }
            
            
            $statement .= " order by b.id desc";
            
            // echo $statement; 

            if ($_REQUEST['atmid'] == '' && $_REQUEST['customer'] == '') { 
                
                $date1 = $_REQUEST['fromdt'];
                $date2 = $_REQUEST['todt'];
                
                $statement = "select a.remarks,a.id AS misid,a.bank,a.customer,a.location,a.zone,a.state,a.city,a.branch,a.created_by,a.bm,b.id,b.mis_id,
                b.atmid,b.component,b.subcomponent,b.engineer,b.docket_no,b.status,b.created_at,b.ticket_id,b.close_date,b.call_type,b.case_type ,
                
                (SELECT CONCAT(name) from vendorUsers WHERE id= b.engineer) AS eng_name,
                (SELECT CONCAT(contact) from vendorUsers WHERE id= b.engineer) AS eng_contact,

                
                IF(b.footage_date = '0000-00-00 00:00:00', '', DATE_FORMAT(b.footage_date, '%Y-%m-%d')) AS footage_date, b.fromtime,b.totime,
                (SELECT name from vendorUsers WHERE id= a.created_by) AS createdBy,

                cc.type,cc.schedule_date,cc.material,cc.material_condition,cc.courier_agency,cc.pod,cc.serial_number,cc.dispatch_date,cc.status_remark,
                cc.material_req_remark,
                cc.material_dispatch_remark,cc.close_remark,cc.last_action_by,cc.created_date
            
                from mis a
                INNER JOIN mis_details b ON b.mis_id = a.id 
                LEFT JOIN (select c.mis_id as cmisid,c.type,c.schedule_date,c.material,c.material_condition,c.courier_agency,c.pod,c.serial_number,c.dispatch_date,c.remark as status_remark,
                IF(c.type='material_requirement',c.remark,'') as material_req_remark,
                IF(c.type='material_dispatch',c.remark,'') as material_dispatch_remark,
                IF(c.type='close',c.remark,'') as close_remark,
                c.created_by as last_action_by,
                c.created_at as created_date
                
                
                from mis_history c  group by c.mis_id
                
                ) AS cc  ON cc.cmisid = a.id 
                
                where 1 and

                    b.mis_id = a.id and
                    b.status in($status) ";
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                $sqlappCount= "select count(1) as total from
                mis a
                INNER JOIN mis_details b ON b.mis_id = a.id 
                LEFT JOIN (select c.mis_id as cmisid,c.type,c.schedule_date,c.material,c.material_condition,c.courier_agency,c.pod,c.serial_number,c.dispatch_date,c.remark as status_remark,
                IF(c.type='material_requirement',c.remark,'') as material_req_remark,
                IF(c.type='material_dispatch',c.remark,'') as material_dispatch_remark,
                IF(c.type='close',c.remark,'') as close_remark,
                c.created_by as last_action_by,
                c.created_at as created_date
                
                
                from mis_history c  group by c.mis_id
                
                ) AS cc  ON cc.cmisid = a.id 
                
                where 1 and
                    b.mis_id = a.id and
                    b.status in($status) ";
                    
                    
                if ($_REQUEST['status'][0]=='close' && count($_REQUEST['status']) == 1 ) {
                    $statement .= " and CAST(b.close_date AS DATE) >= '" . $date1 . "' and CAST(b.close_date AS DATE) <= '" . $date2 . "'";
                    $sqlappCount  .= " and CAST(b.close_date AS DATE) >= '" . $date1 . "' and CAST(b.close_date AS DATE) <= '" . $date2 . "'";
                }else{
                $statement .= "and CAST(b.created_at AS DATE) >= '" . $date1 . "' 
                              and CAST(b.created_at AS DATE) <= '" . $date2 . "'" ;
                              
                  $sqlappCount .= "and CAST(b.created_at AS DATE) >= '" . $date1 . "' 
                      and CAST(b.created_at AS DATE) <= '" . $date2 . "'" ;
                }
                    
                    
                $statement .= " order by b.id desc";
                    
                    
            }
            
            $result = $pdo->prepare($sqlappCount);
            $result->execute();
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $total_records = $row['total'];

            $result2 = $pdo->prepare($statement);
            $result2->execute();
            $row2 = $result2->fetch(PDO::FETCH_ASSOC);
            

            $sql_query = "$statement LIMIT 0, 100";


            
$date = date('Y-m-d');
$date1 = date_create($date);

$i = 0;
$counter = 1;

echo '<table class="table table-hover table-styling table-xs">';
echo '<thead>';
echo '<tr class="table-primary">';
echo '<th>SR</th>';
echo '<th>TicketId</th>';
echo '<th>Customer</th>';
echo '<th>Bank</th>';
echo '<th>Atmid</th>';
echo '<th>Atm Address</th>';
echo '<th>City</th>';
echo '<th>State</th>';

echo '<th>Call Type</th>';
echo '<th>Call Receive From</th>';
echo '<th>Component</th>';
echo '<th>Sub Component</th>';
echo '<th>Current Status</th>';
echo '<th>Status Remarks</th>';
echo '<th>Schedule Date</th>';
echo '<th>Material Condition</th>';
echo '<th>Material</th>';
echo '<th>Material Remark</th>';
echo '<th>Courier Agency (Material Dispatch)</th>';
echo '<th>POD (Material Dispatch)</th>';
echo '<th>Serial Number</th>';
echo '<th>Material dispatch date </th>';
echo '<th>Material Dispatch Remark</th>';

echo '<th>Old Material Details</th>';

echo '<th> DOCKET NO</th>';
echo '<th> REQUEST FOOTAGE DATE</th>';
echo '<th> Time From</th>';
echo '<th> Time To</th>';
echo '<th>Attachment (Close)</th>';
echo '<th>Close Type</th>';
echo '<th>Close Remark</th>';
echo '<th>Last Action By</th>';
echo '<th>Last Action Date</th>';
echo '<th>Call Log Date</th>';
echo '<th>Call Log By</th>';

echo '<th>Aging</th>';
echo '<th>Remark</th>';
echo '<th>Engineer Name</th>';
echo '<th>Engineer Contact Number</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';


$stmt_app = $pdo->prepare($sql_query);
$stmt_app->execute();
while ($sql_result = $stmt_app->fetch(PDO::FETCH_ASSOC)) {
    $id = $sql_result['id'];
    $createdBy = $sql_result['createdBy'];
    $site_eng_contact = $sql_result['eng_name_contact'];
    if ($site_eng_contact == '') {
        $site_engineer = "";
        $site_engineer_contact = "";
    } else {
        $site_engcontact = explode("_", $site_eng_contact);
        $site_engineer = $site_engcontact[0];
        $site_engineer_contact = $site_engcontact[1];
    }

    $mis_id = $sql_result['mis_id'];

    $historydate_query = "SELECT created_at FROM mis_history WHERE mis_id = :id ORDER BY id DESC LIMIT 1";
    $historydate_statement = $pdo->prepare($historydate_query);
    $historydate_statement->bindValue(':id', $id, PDO::PARAM_INT);
    $historydate_statement->execute();
    $created_date_result = $historydate_statement->fetch(PDO::FETCH_NUM);
    $created_date = $created_date_result[0];
    
    $customer = $sql_result['customer'];
    $closed_date = $sql_result['close_date'];
    
    $date2 = $sql_result['created_at'];
    $cust_date2 = date('Y-m-d', strtotime($date2));

    $cust_date2 = date_create($cust_date2);
    $diff = date_diff($date1, $cust_date2);

    $aging_day = $diff->format("%a");
    $atmid = $sql_result['atmid'];






    $mis_his_key = 0;
    $lastaction_query = "SELECT type, created_by, remark, schedule_date, material, material_condition, courier_agency, pod, serial_number, dispatch_date, 
    (SELECT name FROM vendorUsers WHERE id = mh.created_by) AS last_action_by 
    FROM mis_history mh WHERE mis_id = :id ORDER BY id DESC";
$lastaction_statement = $pdo->prepare($lastaction_query);
$lastaction_statement->bindValue(':id', $id, PDO::PARAM_INT);
$lastaction_statement->execute();
if ($lastactionsql_result = $lastaction_statement->fetch(PDO::FETCH_ASSOC)) {

    $his_type = $lastactionsql_result['type'];


    $lastactionuserid = $lastactionsql_result['created_by'];
    $status_remark = $lastactionsql_result['remark'];

    if($mis_his_key==0){
        $last_action_by = $lastactionsql_result['last_action_by'];  
    }
    $mis_his_key = $mis_his_key + 1;
    $schedule_date = "";
    if($his_type=='schedule'){
        $schedule_date = $lastactionsql_result['schedule_date'];
    }


    $material = "";$material_req_remark = "";
    if($his_type=='material_requirement'){
        $material = $lastactionsql_result['material'];
        $material_req_remark = $lastactionsql_result['remark'];
        $material_condition = $lastactionsql_result['material_condition'];
    }
    $courier_agency = "";$pod = "";$serial_number="";$dispatch_date="";$material_dispatch_remark="";
    // if($his_type=='material_dispatch'){
        $courier_agency = $lastactionsql_result['courier_agency'];
        $pod = $lastactionsql_result['pod'];
        $serial_number = $lastactionsql_result['serial_number'];
        $dispatch_date = $lastactionsql_result['dispatch_date'];
        $material_dispatch_remark = $lastactionsql_result['remark'];
    // }
    $close_type = "";$close_remark = "";$close_created_at = "";$attachment="";
    if($his_type=='close'){
        $close_type = $lastactionsql_result['close_type'];
        $close_remark = $lastactionsql_result['remark'];
        $close_created_at = $lastactionsql_result['created_at'];
        $attachment = $lastactionsql_result['attachment'];
    }
    }

    $tr_style = "";
    if ($aging_day > 3 && $status != 'close') {
        $tr_style = 'style="background:#fe5d70c2;color:white;"';
    } elseif ($status == 'close') {
        $tr_style = 'style="background:#0ac282;color:white;"';
    } elseif ($status == 'schedule') {
        $tr_style = 'style="background:#6c757d;color:white;"';
    } elseif ($status == 'open') {
        $tr_style = 'style="background:yellow;color:black;"';
    }


    echo '<tr ' . $tr_style . '>';
    echo '<td>' . $counter . '</td>';
    echo '<td>' . $sql_result['ticket_id'] . '</td>';
    echo '<td>' . $customer . '</td>';
    echo '<td>' . $sql_result['bank'] . '</td>';
    echo '<td>' . $atmid . '</td>';
    echo '<td>' . $sql_result['location'] . '</td>';
    echo '<td>' . $sql_result['city'] . '</td>';
    echo '<td>' . $sql_result['state'] . '</td>';
    echo '<td>' . $sql_result['call_type'] . '</td>';
    echo '<td>' . $sql_result['case_type'] . '</td>';
    echo '<td>' . $sql_result['component'] . '</td>';
    echo '<td>' . $sql_result['subcomponent'] . '</td>';
    echo '<td>' . $status . '</td>';
    echo '<td>' . $status_remark . '</td>';
    echo '<td>' . $schedule_date . '</td>';
    echo '<td>' . $material_condition . '</td>';
    echo '<td>' . $material . '</td>';
    echo '<td>' . $material_req_remark . '</td>';
    echo '<td>' . $courier_agency . '</td>';
    echo '<td>' . $pod . '</td>';
    echo '<td>' . $serial_number . '</td>';
    echo '<td>' . $dispatch_date . '</td>';
    echo '<td>' . $material_dispatch_remark . '</td>';
    echo '<td>' . 'oldMaterialDetails' . '</td>';
    echo '<td>' . $sql_result['docket_no'] . '</td>';
    echo '<td>' . $sql_result['footage_date'] . '</td>';
    echo '<td>' . $sql_result['fromtime'] . '</td>';
    echo '<td>' . $sql_result['totime'] . '</td>';
    echo '<td>';
    if ($attachment != '') {
        echo '<a target="_blank" href="http://cssmumbai.sarmicrosystems.com/css/dash/esir/' . $attachment . '">http://cssmumbai.sarmicrosystems.com/css/dash/esir/' . $attachment . '</a>';
    }
    echo '</td>';
    echo '<td>' . $close_type . '</td>';
    echo '<td>' . $close_remark . '</td>';
    echo '<td>' . $last_action_by . '</td>';
    echo '<td>' . $created_date . '</td>';
    echo '<td>' . $sql_result['created_at'] . '</td>';
    echo '<td>' . $createdBy . '</td>';
    echo '<td>' . $diff->format("%a days") . '</td>';
    echo '<td>' . $sql_result['remarks'] . '</td>';
    echo '<td>' . $sql_result['eng_name'] . '</td>';
    echo '<td>' . $sql_result['eng_contact'] . '</td>';
    echo '</tr>';

    $counter++;
}

    $html = ob_get_clean();
    echo $html;
       }
   }
   ?>