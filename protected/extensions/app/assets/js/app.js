function showSuccess(msg){
    $.notifyBar({
        html: msg,
        cls: "success",
        delay: 3000,
        animationSpeed: "normal"
    });
};
function showError(msg){
    $.notifyBar({
        html: msg,
        cls: "error",
        delay: 3000,
        animationSpeed: "normal"
    });  
};
function showDefault(msg){
    $.notifyBar({
        html: msg,
        delay: 3000,
        animationSpeed: "normal"
    });
};