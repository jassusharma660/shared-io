function saveDocument(doc_id) {
    content = $("#docEditor").val(); 
    $.ajax({
        type: "POST",
        url: "./document.php",
        data: {"action":"save","file":doc_id,"content":content},
        dataType: "text",
        beforeSend: function() {
            alert("sending!")//showProgress();
        },
        complete: function() {
            alert("Done!!");//showProgress(true);
        },
        success: function(response){
           $("#docEditor").val(response);
        }
    });
}

function closeShareDialog() {
    $("#shareDialog").hide();
    $("#selectedShareList").html("");
    $("#liveSearch").val("");
}

function liveSearchNow(q) {
    $.ajax({
        type: "POST",
        url: "./document.php",
        data: {"action":"search","q":q},
        dataType: "text",
        success: function(response){
           $("#liveSearchResults").html(response);
        }
    });
}

$(document).keyup(function(e){
    //Hide popups on esc
    if(e.which==27) { 
        closeShareDialog();
    }
});