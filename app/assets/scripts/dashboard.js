$(function(){
    $("#createDocument").click(function(){
        $("#createDocumentDialog .cancel").click(function(){
            $("#createDocumentDialog").fadeOut(100);
        });
        $("#createDocumentDialog").fadeIn(100);
    });
});

$(document).keyup(function(e){
    //Hide popups on esc
    if(e.which==27) {
        $("#createDocumentDialog").fadeOut(100);
    }
});

function openDocument(doc_id) {
    window.location.href = "/app/view/document.php?action=view&file="+doc_id;
}
function removeDocument(doc_id) {
    window.location.href = "/app/view/document.php?action=remove&file="+doc_id;
}
