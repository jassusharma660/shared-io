function saveDocument(doc_id) {
    content = $("#docEditor").val();
    $.ajax({
        type: "POST",
        url: "./document.php",
        data: {"action":"save","file":doc_id,"content":content},
        dataType: "text",
        beforeSend: function() {
            showProgress();
        },
        complete: function() {
            showProgress(true);
        },
        success: function(response){
           $("#docEditor").val(response);
        }
    });
}

function closeShareDialog() {
    $("#shareDialog").hide();
    $("#liveSearch").val("");
    $("#liveSearchResults").hide();
}

function liveSearchNow(q) {
    $.ajax({
        type: "POST",
        url: "./document.php",
        data: {"action":"search","q":q},
        dataType: "text",
        success: function(response){
           $("#liveSearchResults").show();
           $("#liveSearchResults").html(response);
        }
    });
}

function checkViews() {
    doc_id = $("#doc_id").val();
    $.ajax({
        type: "POST",
        url: "./document.php",
        data: {"action":"viewers","doc_id":doc_id},
        dataType: "text",
        success: function(response){
          if(response == "home")
            window.location.href = "/";
          else
            $('#viewers').html(response);
        }
    });
}

$(document).keyup(function(e){
    //Hide popups on esc
    if(e.which==27) {
        closeShareDialog();
    }
});

$(function(){
    setInterval(checkViews,1000);
});
