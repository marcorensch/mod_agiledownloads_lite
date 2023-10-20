jQuery(document).ready(function($) {

    let initialisedElement = false;

    function showFolderSelectModal(e) {
        e.preventDefault();
        initialisedElement = e.target;
        let row = $(e.target).closest('div.subform-repeatable-group');
        let selectLink = $(row).find('button.open-folderselect-modal');
        let modalId = $(selectLink).attr('href');
        UIkit.modal(modalId).show();
        getFilesystem();
    }

    function setSelectedFolderAsValue(e) {
        let row = $(initialisedElement).closest('div.subform-repeatable-group');
        // remove leading slashes from folderstring
        let valueToStore = selectedFolderPath.replace(/^\/+/, '');
        row.find('.folder-input-field').val(valueToStore);
        row.find('.watched-folder-value').val(valueToStore);
    }


    let selectedFolderPath = null;

    // For existing Rows:
    let selectLinks = $(document).find('button.open-folderselect-modal');

    $(selectLinks).each(function () {
        $(this).on('click', (e) => {
            showFolderSelectModal(e)
        })
    })

    // For newly added Rows:
    $(document).on('subform-row-add', function (event, row) {

        row = !row ? event.detail.row : row;

        let selectLink = $(row).find('button.open-folderselect-modal');

        $(selectLink).on('click', (e) => {
            showFolderSelectModal(e)
        })

    });

    $(document).on('click','.folder',function(){
        let targetId = null;

        selectedFolderPath = $(this).attr('path');
        let forItem = $(this).parents('.uk-modal').attr('data-for');
        targetId = forItem+'_name';        // Name field value (visible)
        if(targetId) $('.set-folder').show();

        // Set selected status
        $('.folder-container .folder.selected-folder').removeClass('selected-folder');
        $(this).addClass('selected-folder');


        // Set folder open / closed icon for clicked element and all others
        $(this).find('span.foldericon').toggleClass('icon-folder-2 icon-folder');
        $('.folder-container .folder:not(.selected-folder)').find('span.foldericon').removeClass('icon-folder').addClass('icon-folder-2 ');


        if($(this).siblings('.subfolders:visible').length){
            $(this).siblings('.subfolders').remove();
        }

        if($(this).children('span.foldericon').hasClass('icon-folder')){
            getSubfolders($(this), selectedFolderPath);
        }

        $('.uk-modal').find('.selected-path').text(selectedFolderPath);
    });

    $(document).on('click','.set-folder',function(e){
        setSelectedFolderAsValue(e);
    })

});