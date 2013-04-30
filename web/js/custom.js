/*Date*/
$(function (){
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 
    var checkin = $('#arrivee').datepicker({
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
        $('#depart')[0].focus();
                              
    }).data('datepicker');
    var checkout = $('#depart').datepicker({
        onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
                       

        checkout.hide();
                              
    }).data('datepicker');
                           
                                              
});   

/* Ajax */

$(document).ready(function() {  
    $("#show_button").hide();
    $("#show_select").hide();
    $("#loading").hide();
    var convertDate= function(date){
        var dateArr = date.split("-");
        var dateFormat = dateArr[2] + "-" + dateArr[1] + "-" + dateArr[0];
        return dateFormat;
                                            
    };
                                             
    var addOption= function(option){
        var txt= '<option value="'+option+'">Chambre '+option+"</option>";
        $("#chambres").prepend(txt);
                                                    
                                     
    }; 
                                             
                                             
                                             
                                             
    $("#nouv_res").submit(function(){
        event.preventDefault();
        var depart= $("#depart").val();
        depart= convertDate(depart);
        var arrivee= $("#arrivee").val();
        arrivee= convertDate(arrivee);
        var litbebe= $("#litbebe").val();
        var data= {
            "depart":depart,
            "arrivee":arrivee,
            "litbebe":litbebe
        };
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

                if(resp.message=='ok'){

                    $.each(resp.chbres,function(k,v){
                        //alert('chambre : '+v);
                        addOption(v);
                    });
                    $("#depart").prop('disabled', true);
                    $("#arrivee").prop('disabled', true);
                    $("#litbebe").prop('disabled', true);
                    $("#show_select").show(1000);
                    $("#show_button").show(1000);

                }else{
                    $("#sub").show();
                    alert('aucune chambre trouvée');
                                                                  
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

