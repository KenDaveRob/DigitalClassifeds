/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    $('#login_register_tabs li a').click(function(e) {
        e.preventDefault()
        $(this).tab('show')
    })

    $('#leftNav .glyphicon').click(function() {
        if ($(this).hasClass('glyphicon-minus')) {
            $(this).parent().removeClass('open');
            $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        } else {
            $(this).parent().addClass('open');
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        }
    });

});

$('.list-group span.caret').click(function() {
    $(this).parent().find('.list-group-item.child').toggle(); //if caret click, expand the hidden sublist
})
