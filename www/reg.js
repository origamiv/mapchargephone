$( document ).ready(function() 
        {
           
                $("#breg").bind("click", function(){
                    var fio=$("#fio").val();
                    var login=$("#login").val();
                    var pass=$("#pass").val();
                    var vozrast=$("#vozrast").val();
                    
                    localStorage.fio=fio;    
                    
                    var url='http://integralkin.ru/projects/mapchargephone/mobile_reg.php?fio='+fio+'&login='+login+'&pass='+pass+'&vozrast='+vozrast;
                    $.get( url, function( data ) {
                            //$( ".result" ).html( data );                        
                        localStorage.user_id=data;
                        alert( "Вы зарегистрированы"+localStorage.user_id);
                        document.location='second.html';
                    });
                    //alert('mmm');
                    
                });               
                
        });



 