define([
        "jquery"
    ], function($){
        "use strict";

        return function(config, element){
            var deleteUrl = config.deleteUrl;
           $(document).on('click',".profile-delete",function(){
                if(confirm("Are you sure you want to delete item?") == true){
                    var customerId = $(this).data("customerid");
                    $.ajax({
                        url: deleteUrl,
                        type: 'post',
                        data: "customer_id="+customerId,
                        success: function(response){
                            if(response.data.success){ 
                                var profile = response.data.profile;
                                $(".profile-delete").hide();
                                $(".profile-image").attr("src",profile); 
                            }  
                        },
                    });
                }
            });
            $('#custom_image').change(function(){
                const file = this.files[0];
                console.log(file);
                if (file){
                  let reader = new FileReader();
                  reader.onload = function(event){
                    console.log(event.target.result);
                    $('.profile-image').attr('src', event.target.result);
                  }
                  reader.readAsDataURL(file);
                }
            });
        }
    }
);