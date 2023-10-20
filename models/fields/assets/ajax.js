jQuery(document).ready(function ($){

    // Remove Restriction handler
    $(document).on('click','.remove-restriction', function(){
        $item = $(this);
        $row = $item.closest('.subform-repeatable-group');
        let path = $row.find('input.folder-input-field').val();
        let config = {
            "path": path,
        };
        let data = JSON.stringify(config);
        $.ajax({
            url: '/index.php?option=com_ajax&module=agiledownloads&method=removeFolderRestriction&data='+data+'&format=json',
            type: "post",
            success :function(response){
                if(response.data.status){
                    $item.parent().siblings('td.status').html('<span class="icon-not-ok"> </span>');
                    $item.hide();
                    $item.siblings('div.add-restriction').show();
                }
            },
            error: function(error){
                console.error(error);
                if(error.hasOwnProperty('responseJSON')){
                    if(error.responseJSON.hasOwnProperty('data')){
                        if(error.responseJSON.data.status){
                            $item.parent().siblings('td.status').html('<span class="icon-not-ok"> </span>');
                            $item.hide();
                            $item.siblings('div.add-restriction').show();
                        }
                    }
                }
            }
        });
    });

    // Add Restriction handler
    $(document).on('click','.add-restriction', function(){
        $item = $(this);
        $row = $item.closest('.subform-repeatable-group');
        let path = $row.find('input.folder-input-field').val();

        let config = {
            "path": path,
        };
        let data = JSON.stringify(config);
        $.ajax({
            url: '/index.php?option=com_ajax&module=agiledownloads&method=addFolderRestriction&data='+data+'&format=json',
            type: "post",
            success :function(response){
                // Change status icon and manage buttons
                if(response.data.status){
                    $item.parent().siblings('td.status').html('<span class="icon-shield" style="color:green"> </span>');
                    $item.hide();
                    $item.siblings('div.remove-restriction').show();
                }
            },
            error: function(error){
                console.error(error);
                if(error.hasOwnProperty('responseJSON')) {
                    if (error.responseJSON.hasOwnProperty('data')) {
                        if (error.responseJSON.data.status) {
                            $item.parent().siblings('td.status').html('<span class="icon-shield" style="color:green"> </span>');
                            $item.hide();
                            $item.siblings('div.remove-restriction').show();
                        }
                    }
                }
            }
        });
    });
});
