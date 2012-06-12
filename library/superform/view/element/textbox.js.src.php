<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Fuentes Abiertas (see README for details)
 *	This file is part of Goteo.
 *
 *  Goteo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Goteo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with Goteo.  If not, see <http://www.gnu.org/licenses/agpl.txt>.
 *
 */
?>
$(function () {

    var li= $('li.element#<?php echo $this['id'] ?>');

    var input = li.children('div.contents').find('input');

    if (input.length) {

       var lastVal = input.val();      
       
       var updating = null;

       var update = function () {
       
           var val = input.val();
       
           clearTimeout(updating);

           if (val != lastVal) {    
           
               lastVal = val;
               
               li.addClass('busy');
                                             
               updating = setTimeout(function () {               
                   window.Superform.update(input, function () {
                       li.removeClass('busy');
                   });
               });  
               
           } else {           
                li.removeClass('busy');
           }
           
       };
       
       input.keydown(function () {
       
           if (!updating) {   
               li.addClass('busy');                       
           } else {               
               clearTimeout(updating);
           }

           updating = setTimeout(function () {
               update();
           }, 700);
       });
      
      input.bind('paste', function () {             
          update();          
      });
       
       input.focus(function () {
       
          updating = null;
          
          input.one('blur', function () {               
              updating = update();
          });
          
       });

    }
   
});

