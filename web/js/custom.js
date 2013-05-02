/*Date*/
(function ($){
    window.dateRivah={
        
        init:function(dateA,dateB){ 
            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 
            var checkin = $('#'+dateA).datepicker({
                onRender: function(date) {
                    return date.valueOf() < now.valueOf() ? 'disabled' : '';
                }
            }).on('changeDate', function(ev) {
                             

                if (ev.date.valueOf() > checkout.date.valueOf()) {
                    var newDate = new Date(ev.date)
                    newDate.setDate(newDate.getDate() + 1);
                    checkout.setValue(newDate);
                }
                checkin.hide();
                $('#'+dateB)[0].focus();
                              
            }).data('datepicker');
            var checkout = $('#'+dateB).datepicker({
                onRender: function(date) {
                    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
                }
            }).on('changeDate', function(ev) {
                       

                checkout.hide();
                              
            }).data('datepicker');
        },
        formatDate: function(date){
            var dateArr = date.split("-");
            var dateFormat = dateArr[2] + "-" + dateArr[1] + "-" + dateArr[0];
            return dateFormat;
                                            
        }
    };                     
                                              
})(jQuery);   

/* Ajax */

$(document).ready(function() {  
    dateRivah.init("arrivee","depart");
    $("#show_button").hide();
    $("#show_select").hide();
    $("#loading").hide();
  
                                             
    var addOption= function(option){
        var txt= '<option value="'+option+'">Chambre '+option+"</option>";
        $("#chambres").prepend(txt);
                                                    
                                     
    }; 
    var createJson= function(){
        var depart= $("#depart").val();
        depart= dateRivah.formatDate(depart);
        var arrivee= $("#arrivee").val();
        arrivee= dateRivah.formatDate(arrivee);
        var litbebe= $("#litbebe").val();
        var chambres= $("#chambres").val();// on récupère les chambres même si le champs est hidden!

        var data= {
            "depart":depart,
            "arrivee":arrivee,
            "litbebe":litbebe,
            "chambres": chambres
        };
        return data;
    };
    // Fonction confirmation réservation
    var confirmReservation= function(){
        event.preventDefault();
        var url= Routing.generate('hotel_reservation_confirm');
        var data= createJson();
        $("#show_select").hide();
        $("#show_button").hide();
        $("#loading").show();

        $.ajax({
            url: url,
            dataType: 'json',
            type: "POST",
            data:data,
            complete: function(xhr,result){
                $("#loading").hide(); // on cache le loading
                $("#chambres").empty(); // on vide la liste de sélection          
                document.forms.nouv_res.reset();// on reset le formulaire de réservation
                $("#depart").prop('disabled', false);
                $("#arrivee").prop('disabled', false);
                $("#litbebe").prop('disabled', false);
                $("#sub").show();
                if(result!= "success") {
                    alert("Erreur");
                    return;
                }
                resp= $.parseJSON(xhr.responseText);
                //modal_resa modal_message
                $('#modal_message').html('').append(resp.message);
                $('#modal_resa').modal("show");
                
            }
            
            
            
        });
        return false;
         
    }                                        
                                             
    // Fonction recherche de chambre                                     
                                             
    $("#nouv_res").submit(function(){
        event.preventDefault();
        var data= createJson();
        var url= Routing.generate('hotel_reservation_search');
        $("#sub").hide();
        $("#loading").show();
        $.ajax({
            url: url,
            dataType: 'json',
            type: "POST",
            data:data,
            //data:data,
            complete: function(xhr, result){
                $("#loading").hide();

                if(result!= "success") {
                    $("#sub").show();

                    alert("Erreur");
                    return;
                }
                resp= $.parseJSON(xhr.responseText);

                if(resp.status=='ok'){

                    $.each(resp.chbres,function(k,v){
                        //alert('chambre : '+v);
                        addOption(v);
                    });
                    $("#depart").prop('disabled', true);
                    $("#arrivee").prop('disabled', true);
                    $("#litbebe").prop('disabled', true);
                    $("#show_select").show(1000);
                    $("#show_button").show(1000);
                    $("#confirm").on("click",function(e){
                        confirmReservation();
                    }); // au click sur le bouton confirmer
                }else{
                    $("#sub").show();
                    $('#modal_message').html('').append(resp.message);
                    $('#modal_resa').modal("show");
                                                                  
                }
                   
            //var route="http://bittich.be/Hotel/web/fr/reservation/nouvelle-reservation?id=PLACEHOLDER";
            //window.location.href= route.replace("PLACEHOLDER",resp);
            // $(location).attr('href',);
            }
        }
                  
        );  
        return false;
    });
});

