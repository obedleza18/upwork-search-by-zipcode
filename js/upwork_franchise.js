/**
 *  Custom Functions for Upwork Franchise Map Plugin
 */

( function( $ ) {
    'use strict';

    $( document ).ready( function() {
        
        /**
         *  Function that add an input text field and triggers the AJAX function to display the Franchises options
         *  on a modal window.
         */

        var main_header = $( 'header.main-header .limit-wrapper .header-contents .second-row' );
        var search_box = $( '<input id="upwork_zip_code"/>' )
        var submit_btn = $( '<button class="vamtam-button accent1 button-filled hover-accent3 upwork-submit-btn">' + upwork_franchise_custom_object.sumbit_label + '</button>' );
        main_header.append( upwork_franchise_custom_object.input_label );
        main_header.append( search_box );
        main_header.append( submit_btn );

        $( '.upwork-submit-btn' ).on( 'click', function() {
            var zip_code_sanitized_value = Math.abs( parseInt( $( '#upwork_zip_code' ).val() ) );
            var ajax_response;

            $.confirm({
                title: 'Franchises Options',
                content: function() {
                    var self = this;
                    return $.ajax( { 
                        data: {
                            action: 'upwork_get_franchises_options',
                            zip_code: zip_code_sanitized_value
                        },
                        type: 'post',
                        url: upwork_franchise_custom_object.ajax_url,
                        } ).done( function( response ) {
                            
                            if ( response.success ) {
                                var content = '<table>';
                                content += '<tr>';
                                content += '<th>' + upwork_franchise_custom_object.franchise_id +' </th>';
                                content += '<th>' + upwork_franchise_custom_object.franchise_name +' </th>';
                                content += '<th>' + upwork_franchise_custom_object.phone +' </th>';
                                content += '<th>' + upwork_franchise_custom_object.website +' </th>';
                                content += '<th>' + upwork_franchise_custom_object.email +' </th>';
                                content += '</tr>';
                                response.data.forEach( function( current_franchise ) {
                                    content += '<tr>';
                                    content += '<td>' + current_franchise['franchise_id'] + '</td>';
                                    content += '<td>' + current_franchise['franchise_name'] + '</td>';
                                    content += '<td>' + current_franchise['phone'] + '</td>';
                                    content += '<td>' + current_franchise['website'] + '</td>';
                                    content += '<td>' + current_franchise['email'] + '</td>';
                                    content += '</tr>';
                                } );
                                content += '</table>';
                                self.setContent( content );
                                console.log( response );
                            }

                        } ).fail( function( response ) {
                            console.log('Error');
                        } );
                },
                columnClass: 'medium',
            });
        } );
    } );

    jQuery( window ).load(
        function () {
            /*  Nothing yet */
        }
    );

})( jQuery );























