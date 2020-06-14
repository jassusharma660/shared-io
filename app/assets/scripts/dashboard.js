$(function(){
    $("#createDocument").click(function(){
        $("#createDocumentDialog .cancel").click(function(){
            $("#createDocumentDialog").hide();
        });
        $("#createDocumentDialog").show();
    });
});

$(document).keyup(function(e){
    //Hide popups on esc
    if(e.which==27) { 
        $("#createDocumentDialog").hide();
    }
});

function openDocument(doc_id) {
    window.location.href = "./document.php?action=view&file="+doc_id;
}
function removeDocument(doc_id) {
    window.location.href = "./document.php?action=remove&file="+doc_id;
}