$(document).ready(function(){
    var flag=false;
    $('#togglePassword').click(function(){
        $('#togglePassword').toggleClass('bi-eye-slash bi-eye');
        $('#newPassword').attr('type',function(index,attr){
            flag=!flag;
            return flag==true? 'text':'password';
        }) 
    });

});