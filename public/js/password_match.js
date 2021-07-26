window.onload = function () {
document.getElementById("reg_password").onchange = validatePassword;
document.getElementById("reg_password_proof").onchange = validatePassword;
}
function validatePassword(){
var pass2=document.getElementById("reg_password_proof").value;
var pass1=document.getElementById("reg_password").value;
if(pass1!=pass2)
document.getElementById("reg_password_proof").setCustomValidity("Пароли не совпадают");
else
document.getElementById("reg_password_proof").setCustomValidity('');
//empty string means no validation error
}
