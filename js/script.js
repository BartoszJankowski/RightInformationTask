/**
 * Function use ajax to procces data and relonad content in table after
 * @param person_id
 * @constructor
 */
function DeletePerson(person_id){

    $.ajax(
        {
            url:"index.php",
            type:"POST",
            data:{"action":"delete","id":person_id},
            beforeSend : function () {


            },
            success : function (resp) {
                console.log(resp);
                if(resp.result){
                    AppendInfo("The person (id: "+person_id+") has been removed.", 'success');
                    ReloadTable();
                } else {
                    AppendInfo("Error. Couldn't remove person.", 'error');
                }

            },
            error : function () {
                //TODO : show error
                AppendInfo("An internal server problem has occurred.", 'error');
            },
            complete : function () {


            }
        }
    )
}

/**
 * Appends notification info in floating box
 * @param info
 * @param typ
 * @constructor
 */
function AppendInfo(info, typ){

    var notifyBox = $("#floatingInfo");
    var notification = $('<DIV class="notification '+typ+' " >');
    notification.text(info);

    notifyBox.append(notification);
    notification.fadeIn(700,
        function (){$(this).fadeOut(8000,function(){$(this).remove();});}
    );

}

/**
 * Reloads table with pagination underneeth
 * @constructor
 */
function ReloadTable() {

    $("<DIV>").load(location.href+" #main",function(responseTxt, statusTxt, xhr){
        $("#main").replaceWith($(this).html());
    })
}

/**
 * Loads modal {ADD new person modal} from modals.php
 *
 * @constructor
 */
//TODO: make one simple load moad func
function LoadModal(){
    $('#modalContent').html("<div class='loadingDiv'><i class=\"fas fa-spin fa-spinner\"></i> Trwa ładowanie...</div>").load('modals.php?add=1 #modal_person',function () {
        //setCalendarForInput();
    });
}

/**
 * Loads modal {Edit Person Modal} from modals.php
 *
 * @constructor
 */
//TODO: make one simple load moad func
function LoadModalEdit(id){
    $('#modalContent').html("<div class='loadingDiv'><i class=\"fas fa-spin fa-spinner\"></i> Trwa ładowanie...</div>").load('modals.php?edit='+id+' #modal_person',function () {
        //setCalendarForInput();
    });
}

/**
 * responds to action ONSUBMIT in form - proceed ajax and reloads content
 * @param form
 * @returns {boolean}
 * @constructor
 */
//TODO: almost cloned code - refractor to obtain one simple Adding/Editing func
function AddPerson(form){
    var data = $(form).serialize();

    $.ajax(
        {
            url: 'index.php',
            type:"POST",
            data:data,
            beforeSend : function () {
                console.log(data);
             },
            success : function (resp) {
                console.log(resp);
                if(resp.result){
                    AppendInfo("New person  has been added.", 'success');
                    ReloadTable();
                    $("#modal").modal('hide');
                } else {
                    AppendInfo("Error. Couldn't create person.", 'error');
                }

            },
            error : function () {
                AppendInfo("An internal server problem has occurred.", 'error');
            },
            complete : function () {

            }
        }
    );

    return false;
}

/**
 * responds to action ONSUBMIT in form - proceed ajax and reloads content
 * @param form
 * @returns {boolean}
 * @constructor
 */
function EditPerson(form){
    var data = $(form).serialize();

    $.ajax(
        {
            url: 'index.php',
            type:"POST",
            data:data,
            beforeSend : function () {
                console.log(data);
            },
            success : function (resp) {
                console.log(resp);
                if(resp.result){
                    AppendInfo("Editing the person was a success.", 'success');
                    ReloadTable();
                    $("#modal").modal('hide');
                } else {
                    AppendInfo("Error. Couldn't edit person.", 'error');
                }

            },
            error : function () {
                AppendInfo("An internal server problem has occurred.", 'error');
            },
            complete : function () {

            }
        }
    );

    return false;
}