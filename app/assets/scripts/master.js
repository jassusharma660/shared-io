
function showProgress(stop) {
    if ($("#progress").length === 0 && stop!=true) {
        // inject the bar..
        $("body").append($("<div><b></b><i></i></div>").attr("id", "progress"));

        // animate the progress..
        $("#progress").animate({"width":"110%"},100, function() {
            $(this).css({"opacity":"0"},3000).animate({"opacity":"1"},1000).animate({"width":"0"},0,function(){
                $(this).remove();
                showProgress();
            });
        });
    } 
    else {
        $("#progress").remove();
    }
}
