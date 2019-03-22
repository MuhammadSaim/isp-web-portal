//ajax function call to select
function getDepartmentsSelection(url, el, data = null) {
    $.ajax({
        url: url,
        data: data,
        type: 'get',
        success: function(data){
            if(data.length > 0){
                for(var i=0; i<data.length; i++){
                    $(el).append(`<option value="${data[i].id}">${data[i].department}</option>`);
                }
            }else{
                $(el).attr('disabled', true);
            }
        }
    });
}//ajax call function ends here