jQuery(document).ready(function($){
    $('.set-folder').hide();
    getFilesystem();
    let selected = null;
    $(document).on('click','.loadFoldersBtn',function(){
        //console.log('clicked');
        getFilesystem();
    });

    // Fix für kürzlich hinzugefügte elemente
    $(document).on('subform-row-add', function(event, row){
        let elemId = jQuery(row).find('input.watched-folder-value').attr('id');
        let thenum = elemId.match(/\d+/)[0]
        //console.log(elemId);
        //Set Visible Elements ID's and relationships
        jQuery(row).find('input#jform_params__watched_folders__watched_foldersX__watched_folder_name').attr('id', elemId+'_name').attr('data-target', elemId+'_id');
        jQuery(row).find('a.open-folderselect-modal').attr('data-for', elemId);

        // Change ID for MODAL:
        jQuery(document).find('div#ModalSelectagiledownloads_jform_params__watched_folders__watched_foldersX__watched_folder_modal').attr('data-for', elemId);
        jQuery(document).find('div#ModalSelectagiledownloads_jform_params__watched_folders__watched_foldersX__watched_folder_modal').attr('id', 'ModalSelectagiledownloads_jform_params__watched_folders__watched_folders'+thenum+'__watched_folder_modal');
        jQuery(row).find('a.open-folderselect-modal').attr('uk-toggle', 'target: #ModalSelectagiledownloads_jform_params__watched_folders__watched_folders'+thenum+'__watched_folder_modal');
        //console.log(jQuery('#ModalSelectagiledownloads_jform_params__watched_folders__watched_folders'+thenum+'__watched_folder_modal'))
        getFilesystem();
    })

    // Add data-for attribute if a newly added row
    /* Now added when row gets added
    $(document).on('click','a.open-folderselect-modal',function(){
        let $parent = $(this).parent('span.input-append');
        $parent.siblings('.modal').attr('data-for',$(this).attr('data-for'));
    });
     */
    /*

    */
    $(document).on('click','.folder',function(){
        //$(this).append('<div class="load-folders-spinner" style="margin-left:10px; font-size: 8px">Loading</div>')
        let targetId = null;
        let hiddenTargetId = null;
        let newHiddenTargetId = null;

        selected = $(this).attr('path');
        let forItem = $(this).parents('.uk-modal').attr('data-for');
        targetId = forItem+'_name';        // Name field value (visible)
        hiddenTargetId = forItem+'_id';    // Hidden field that stores the value
        newHiddenTargetId = forItem;       // Hidden field id when added but not yet saved
        //console.log(forItem);
        //console.log(targetId);
        //$('.set-folder').html('Use "'+$(this).find('.foldername').text()+'" Folder');
        if(targetId) $('.set-folder').show();
        //console.log(selected);

        // Set selected status
        $('.folder-container .folder.selected-folder').removeClass('selected-folder');
        $(this).addClass('selected-folder');


        // Set folder open / closed icon for clicked element and all others
        $(this).find('span.foldericon').toggleClass('icon-folder-2 icon-folder');
        $('.folder-container .folder:not(.selected-folder)').find('span.foldericon').removeClass('icon-folder').addClass('icon-folder-2 ');


        if($(this).siblings('.subfolders:visible').length){
            //console.log('hasVisibleSubFolders');
            $(this).siblings('.subfolders').remove();

        }else{
            //console.log('has NO visible SubFolders');

        }
        if($(this).children('span.foldericon').hasClass('icon-folder')){
            getSubfolders($(this), selected);
        }

        // ModalSelectagiledownloads_jform_params__watched_folders__watched_folders0__watched_folder_modal
        //
        // remove leading slashes from folderstring
        let selectedVal = selected.replace(/^\/+/, '');

        // Set Folder
        $('.uk-modal').on('click','.set-folder',function(){
            //console.log(targetId);
            //console.log(hiddenTargetId);
            //console.log(newHiddenTargetId);
            $('input#'+targetId).val(selectedVal);
            $('input#'+hiddenTargetId).val(selectedVal);
            $('input#'+newHiddenTargetId).val(selectedVal);
        });

    });
});

jQuery(document).ready(function($){
    $(document).on('keyup','.folder-input-field',function(){
        let targetId = $(this).attr('data-target')+'_id';
        $('#'+targetId).val($(this).val());
    });
});

function getFilesystem(){
    jQuery.when(getFilesystemAjax()).then(function(response){
        if(response.success) {
            //console.log('Success');
            //console.log(response.data);
            buildHTML(response.data);
        }else{
            console.error('Error while fetching folders');
            console.log(response);
        }
    });
}

function getSubfolders($target, path){
    jQuery.when(getFilesystemAjax(path)).then(function(response){
        if(response.success) {
            //console.log('Success');
            //console.log(response.data);
            if(response.data.length){
                addHTML($target, response.data);
                /*
                setTimeout(function(){
                    jQuery('.load-folders-spinner').fadeOut('slow',function(e){
                        e.remove();
                    });
                    addHTML($target, response.data);
                },1000);
*/
            }else{
                //console.log('empty folder')
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
        'module': 'agiledownloads_lite',
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
