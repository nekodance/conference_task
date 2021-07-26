$('body').on('click', '.reg-password-control', function(){
	if ($('#reg_password').attr('type') == 'password' ){
		$(this).addClass('view');
		$('#reg_password').attr('type', 'text');
		$('#reg_password_proof').attr('type', 'text');
	} else {
		$(this).removeClass('view');
		$('#reg_password').attr('type', 'password');
		$('#reg_password_proof').attr('type', 'password');
	}
	return false;
});