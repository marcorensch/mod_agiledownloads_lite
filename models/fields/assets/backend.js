// JS Document to adapt Styling our needs in Joomla 3.x
jQuery(document).ready(function(){
    jQuery(document).on('subform-row-add', function(event, row){
        jQuery(row).find('select').chosen();
    })

    jQuery('.external-src-subform-section-title').parents('.controls').css('margin-left','0px');
    jQuery('#jform_params_ext_src_grp-lbl').parents('.control-label').css({'display':'none','width':'0','padding':'0','margin':'0'});
});
