(function () {
	"use strict";

	var siteBuilder = require('./builder.js');
	var appUI = require('../shared/ui.js').appUI;

	var revisions = {
        
        selectRevisions: document.getElementById('dropdown_revisions'),
        buttonRevisions: document.getElementById('button_revisionsDropdown'),
    
        init: function() {
            
            $(this.selectRevisions).on('click', 'a.link_deleteRevision', this.deleteRevision);
            $(this.selectRevisions).on('click', 'a.link_restoreRevision', this.restoreRevision);
            $(document).on('changePage', 'body', this.loadRevisions);
            
            //reveal the revisions dropdown
            $(this.buttonRevisions).show();
            
        },
        
        
        /*
            deletes a single revision
        */
        deleteRevision: function(e) {
            
            e.preventDefault();
            
            var theLink = $(this);
            
            if( confirm('Are you sure you want to delete this revision?') ) {
                
                $.ajax({
                    url: $(this).attr('href'),
                    method: 'post',
                    dataType: 'json'
                }).done(function(ret){
				
                    if( ret.code === 1 ) {//if successfull, remove LI from list
					
                        theLink.parent().fadeOut(function(){
						
                            $(this).remove();
												
                            if( $('ul#dropdown_revisions li').size() === 0 ) {//list is empty, hide revisions dropdown				
                                $('#button_revisionsDropdown button').addClass('disabled');
                                $('#button_revisionsDropdown').dropdown('toggle');
                            }
                        
                        });
                    
                    }				
                
                }).fail(function (jqXHR, textStatus, errorThrown) {

                    if ( jqXHR.getResponseHeader('Refresh') !== null && jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1 ) {

                        siteBuilder.site.setPendingChanges(false);
                        location.href = appUI.siteUrl + "auth/?url=" + encodeURIComponent(location.href);

                    }

                });
            
            }	

            return false;
            
        },
        
        
        /*
            restores a revision
        */
        restoreRevision: function() {
            
            if( confirm('Are you sure you want to restore this revision? This would overwrite the current page. Continue?') ) {
                return true;
            } else {
                return false;
            }
            
        },
        
        
        /*
            loads revisions for the active page
        */
        loadRevisions: function() {
                        		
            $.ajax({
                url: appUI.siteUrl+"sites/getRevisions/"+siteBuilder.site.data.sites_id+"/"+siteBuilder.site.activePage.name
            }).done(function(ret, textStatus, jqXHR){

                if ( jqXHR.getResponseHeader('Refresh') !== null && jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1 ) {

                    siteBuilder.site.setPendingChanges(false);
                    location.href = appUI.siteUrl + "auth/?url=" + encodeURIComponent(location.href);

                }
							
                if( ret === '' ) {
					
                    $('#button_revisionsDropdown button').each(function(){
                        $(this).addClass('disabled');
                    });
                        
                    $('ul#dropdown_revisions').html( '' );
                        
                } else {
                            
                    $('ul#dropdown_revisions').html( ret );
                    $('#button_revisionsDropdown button').each(function(){
                        $(this).removeClass('disabled');
                    });
                        
                }

            });
    
        }
        
    };
    
    revisions.init();

}());