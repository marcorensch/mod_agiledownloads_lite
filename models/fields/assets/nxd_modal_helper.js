jQuery(document).ready(function($){
    $(document).on('keyup','.folder-input-field',function(){
        let targetId = $(this).attr('data-target')+'_id';
        $('#'+targetId).val($(this).val());
    });
});

function getFilesystem(){
    jQuery.when(getFilesystemAjax()).then(function(response){
        if(response.success) {
            buildHTML(response.data);
        }else{
            console.error('Error while fetching folders');
            console.log(response);
        }
    }, function(e){
        console.log('ERROR');
        console.log(e)
    });

}

function getSubfolders($target, path){
    jQuery.when(getFilesystemAjax(path)).then(function(response){
        if(response.success) {
            console.log(response)
            if(response.data.length){
                addHTML($target, response.data);
            }else{
                setTimeout(function(){
                    jQuery('.load-folders-spinner').fadeOut('slow',function(e){
                        e.remove();
                    });
                },1000);
            }
        }else{
            console.log('ERROR');
            console.log(response);
        }
    });
}

function getFilesystemAjax(path = ''){
    let data = {
        path:path,
    };
    let request = {
        'data': JSON.stringify(data),
        'format': 'json',
        'option':'com_ajax',
        'module': 'agiledownloads',
        'method': 'getFoldersTree'
    };
    return jQuery.ajax({
        url: '/index.php',
        type : 'GET',
        data: request,
        dataType:'json'
    });
}

function addHTML($target, folders){
    let subFolders = '';
    for( let folder of folders){
        //console.log(folder);
        subFolders += '<div class="folder" path="'+folder.relname+'"><span class="foldericon icon-folder-2"></span> <span class="foldername">'+folder.name+'</span></div>';
    }
    $target.after( '<div class="subfolders inset-left">'+subFolders+'</div>' );
}

function buildHTML(folders){
    let basement = '';
    for( let folder of folders){
        //console.log(folder);
        basement += '<div class="folder" path="'+folder.relname+'"><span class="foldericon icon-folder-2"></span> <span class="foldername">'+folder.name+'</span></div>';
    }
    jQuery('.folder-container').html( '<div>'+basement+'</div>' );
}
