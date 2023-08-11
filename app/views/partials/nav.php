<?php

$pdo = Connection::make();
$userid = $_SESSION['SERVICE_userid'];

$userSql = "select * from vendorUsers where id=" . $userid;

$statement = $pdo->prepare($userSql);
$statement->execute();
$usersql_result = $statement->fetchAll(PDO::FETCH_ASSOC);
$RailTailVendorID = $usersql_result[0]['vendorId'];
$permission = $usersql_result[0]['servicePermission'];
$permission = explode(',', $permission);
sort($permission);

$cpermission = json_encode($permission);
$cpermission = str_replace(['[', ']', '"'], '', $cpermission);
$cpermission = explode(',', $cpermission);
$cpermission = "'" . implode("', '", $cpermission) . "'";

$mainmenu = [];
// echo '<pre>';print_r($permission);echo '</pre>';
foreach ($permission as $key => $val) {
    $sub_menu_sql = $pdo->prepare("select * from sub_menu where id='" . $val . "' and isService=1");
    $sub_menu_sql->execute();
    $usersql_result = $sub_menu_sql->fetchAll(PDO::FETCH_ASSOC);

    if (count($usersql_result) > 0) {
        $mainmenu[] = $usersql_result[0]['main_menu'];
    }
}
// echo '<pre>';print_r($mainmenu);echo '</pre>';
$mainmenu = array_unique($mainmenu);

//echo '<pre>';print_r($mainmenu);echo '</pre>';die;
?>
    


<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">



<nav class="pcoded-navbar">
                        <div class="pcoded-inner-navbar main-menu">
                            <div class="pcoded-navigatio-lavel">Navigation</div>
                        
                            <ul class="pcoded-item pcoded-left-item">
                                
                        <li class="">
                                    <a href="./">
                                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                                        <span class="pcoded-mtext">Home</span>
                                    </a>
                                </li>
                        
                                
                        
                        <?
                        
                        foreach($mainmenu as $menu=>$menu_id){
                        

                        $menu_sql = $pdo->prepare("select * from main_menu where id='".$menu_id."' and isService=1");
                        $menu_sql->execute();
                        
                        $menu_sql_result = $menu_sql->fetchAll(PDO::FETCH_ASSOC);
                        $main_name = $menu_sql_result[0]['name']; 
                        $icon = $menu_sql_result[0]['icon'];
                        ?>

                            <li class="pcoded-hasmenu">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon">
                                            
                                            <?
                                            if($main_name=='Admin'){
                                                echo '<i class="fa fa-american-sign-language-interpreting"></i>';                                                
                                            }else if($main_name=='Sites'){
                                                echo '<i class="fa fa-sitemap"></i></span>';   
                                            } else if($main_name=='mis'){
                                                echo '<i class="feather icon-gitlab"></i>';
                                            }else if($main_name=='Accounts'){
                                                echo '<i class="feather icon-pie-chart"></i>';
                                            }else if($main_name=='Report'){
                                                echo '<i class="feather icon-box"></i>';
                                            }else if($main_name=='Footage Request'){
                                                echo '<i class="feather icon-image"></i>';
                                            }else if($main_name=='Project'){
                                                echo '<i class="feather icon-aperture rotate-refresh"></i>';
                                            }else if($main_name=='Feasibility'){
                                                echo '<i class="feather icon-gitlab"></i>';
                                            }else if($main_name=='Leads'){
                                                echo '<i class="fa fa-list-alt"></i>';
                                            }else if($main_name=='Inventory'){
                                                echo '<i class="feather icon-pie-chart"></i>';
                                            }
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            ?>
                                                    </span>
                                                    <span class="pcoded-mtext"><? echo $main_name; ?></span>
                                    </a>
                                    
                                    
                                    <ul class="pcoded-submenu">                                    
                                        <?

                                        $submenu_sql = $pdo->prepare("select * from sub_menu where main_menu = '".$menu_id."' and id in ($cpermission) and isService=1");
                                        $submenu_sql->execute();

                                        while($submenu_sql_result = $submenu_sql->fetch(PDO::FETCH_ASSOC)){ 
                                        $page = $submenu_sql_result['page'];
                                        $submenu_name = $submenu_sql_result['sub_menu'];
                                         
                                        ?>
                                            
                                            <li class=" ">
                                                <a href="<?= pathinfo($page, PATHINFO_FILENAME) ?>">
                                                    <span class="pcoded-mtext"><? echo $submenu_name; ?></span>
                                                </a>
                                            </li>
                                        
                                        <? } ?>
                                        
                                    </ul>
                                </li>
                                
                                
                        <? }
                        
                        ?>
                        <li class="">
                                    <a href="logout">
                                        <span class="pcoded-micon"><i class="feather icon-log-out"></i></span>
                                        <span class="pcoded-mtext">Logout</span>
                                    </a>
                                </li>
                                
                            
                            </ul>
                        </div>
                    </nav>

                    
                    
                    
                    <script>
window.addEventListener('load', () => {
  // Delay the override to ensure the CDN file has loaded
  setTimeout(() => {
    const divElement = document.querySelector('#pcoded'); // Select the <div> element
    divElement.setAttribute('nav-type', 'st5'); // Override the nav-type attribute value to "st6"
  }, 1000); // Adjust the delay (in milliseconds) based on the CDN file's loading time
});

                    </script>



<!-- <nav>
    <ul>
        <li><a href="./index">Home</a></li>
        <li><a href="./about">About</a></li>
        <li><a href="./users">Users</a></li>
        <?php if (isset($_SESSION['SERVICE_username'])): ?>
            <li><?= $_SESSION['SERVICE_username'] ?></li>
            <li><a href="./logout">Logout</a></li>
        <?php else: ?>
            <li><a href="./login">Login</a></li>
        <?php endif; ?>
    </ul>
</nav> -->
