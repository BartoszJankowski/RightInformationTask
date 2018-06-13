<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 17:10
 */
include "php/config.php";

$pm = new PersonManagment();
$pagination = new Pagination();

/**
 * Strip tags and etc. from $_POST and $_GET
 */
test_input($_GET);
test_input($_POST);

/**
 * Part for
 */
//TODO: might be deleted after replacing .js to communicate with endpoint
if(isset($_POST['action'])){
    $output = ['result' => false, 'action'=>$_POST['action']];
    switch ($_POST['action']){
        case 'delete':
            $output['result'] = $pm->DeletePerson($_POST['id']);
            break;
        case 'addperson':
	        $output['result'] = $pm->AddPerson($_POST);
            break;
        case 'editperson':
	        $output['result'] = $pm->EditPerson($_POST['id'],$_POST);
            break;
    }

	header("Content-Type: application/json; charset=UTF-8");
	echo json_encode($output);
	die;
}


/**
 * Simple select and creates table for display
 */
$pm->SelectAll($_GET['filter']);
$table = new TableDisplay($pm->GetPersons());

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
		<title>Right Information task</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="./js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="./js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="./js/script.js?ver=1.1"></script>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/fontawesome-all.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css?ver=1.1" />
	</head>

	<body>

    <div id="main">
	    <?php
	    /**
	     * Display table and pagination
	     */
	    $table($_GET['page']);
	    $pagination->DisplayPagination($_GET['page'], $pm->GetPersons() );
	    ?>
    </div>


    <div class="text-center">
        <button onclick="LoadModal()" class="btn btn-primary mt-2" data-toggle="modal" data-target="#modal" ><i class="fas fa-plus"></i> Add new person</button>
    </div>



    <div id="floatingInfo"></div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div id="modalContent" class="modal-dialog modal-dialog-centered" role="document">

        </div>
    </div>
    </body>
</html>