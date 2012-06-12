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

if (!('Superform' in window)) {

    $(function () {
    
        var sf = $('div.superform#<?php echo $this['id'] ?>');

        window.Superform = {        
            
            bindElement: function (el, al) {

                var handler = function (event) {
                
                    var id = $(this).attr('id');

                    var frm = $(this).parents('form').eq(0);                                        
                    
                    if (frm) {
                                        
                        if (frm.__sf_fb && frm.__sf_fb !== id) {
                            frm.find('div.feedback#superform-feedback-for-' + frm.__sf_fb).fadeOut(frm.__sf_as);                            
                        }
                        
                        frm.find('div.feedback#superform-feedback-for-' + frm.__sf_fb).fadeOut(frm.__sf_as);
                        frm.__sf_fb = id;
                        
                    }

                    event.stopPropagation();

                };                

            },
            
            updateElement: function (el, nel, html) {                            
                        
                try {       
                
                    el = $(el);
                    
                    el.addClass('updating busy');                                        
                    
                    if (!nel) {
                    
                        if (html) {
                        
                            var el_id = el.attr('id');
                            
                            var s = html.indexOf('<!-- SFEL#' + el.attr('id') + ' -->', 0);

                            if (s > 0) {

                                var e = html.indexOf('<!-- /SFEL#' + el.attr('id') + ' -->', s);

                                if (e > 0) {

                                    var nelhtml = html.substring(s, e);

                                    var wrp = $('<div></div>');                                                        
                                    wrp[0].innerHTML = nelhtml;
                                    delete nelhtml;                                                                                                        

                                    nel = wrp.children().first();                               

                                } else {
                                    throw new Exception;
                                }

                            } else {
                                throw new Exception;
                            }
                            
                        } else {
                            throw new Exception;
                        }
                    
                    } else {
                        nel = $(nel);
                    }   
                    
                    var ev = $.Event('sfbeforeupdate');

                    el.trigger(ev, [el, nel]);
                                                            
                    if (ev.isDefaultPrevented()) {
                        
                        throw new Exception;
                    
                    } else {
                    
                        // Get focused element
                        var focused = $(':focus').first();                                                

                        // Contents
                        var contents = el.children('div.contents');
                        var ncontents = nel.children('div.contents');

                        if (contents.length) {                                
                            if (!ncontents.length) {                                
                                contents.slideUp('slow');
                                contents.remove();
                            } else if (!focused.length || (!$.contains(contents[0], focused[0]))) {
                                contents.replaceWith(ncontents);
                            }
                        } else if (ncontents.length) {
                            el.append(ncontents);
                        }

                        // Feedback
                        var feedback = el.children('div.feedback');          
                        var nfeedback = nel.children('div.feedback');
                        if (nfeedback.length) {                                                                        
                            if (feedback.length) {
                                feedback.html(nfeedback.html());
                            } else {
                                el.append(nfeedback);
                            }                                        
                        } else if (feedback.length) {
                            feedback.remove();
                        }
                        
                        var ol_children = el.children('div.children').children('div.elements').children('ol');
                                                                        
                        var children = ol_children.children('li.element');
                        
                        var nchildren = nel.children('div.children').children('div.elements').children('ol').children('li.element');
                        
                        if (!children.length && nchildren.length) {
                            el.children('div.children').remove();
                            var c = $('<div class="children"><div class="elements"><ol></ol></div></div>');
                            el.append(c);
                            ol_children = el.children('div.children').children('div.elements').children('ol')
                        }                                                
                        
                        var m = 0;
                        
                        nchildren.each(function (i, nchild) {
                            
                            var $nchild = $(nchild);
                            var nchild_id = $nchild.attr('id');
                                
                            var $child = children.filter('li.element#' + nchild_id);
                            
                            if ($child.length) {
                                Superform.updateElement($child, $nchild, html);                             
                                $child.appendTo($child.parent());
                            } else {
                                $nchild.hide();
                                $nchild.appendTo(ol_children);
                                $nchild.slideDown('slow');                                
                            }
                            
                            m++;
                                                                                
                        });                        
                        
                        children.each(function (i, child) {
                            var $child = $(child);
                            if (!nchildren.filter('li.element#' + $child.attr('id')).length) {
                                $child.slideUp('slow', function () {
                                    $child.remove();
                                });
                            }
                        });                                                
                        
                        /*
                        var children = el.children('div.children').children('div.elements').children('ol').children('li.element');
                        var nchildren = nel.children('div.children').children('div.elements').children('ol').children('li.element');                                              
                        
                        children.each(function (i, child) {

                            var nchild = nchildren.filter('li.element#' + child.id);

                            if (nchild.length) {               
                                Superform.updateElement(child, nchild, html);
                                nchildren = nchildren.not(nchild);
                            } else {
                                var ev = $.Event('sfbeforeremove');
                                $(child).trigger(ev, [child]);
                                if (!ev.isDefaultPrevented()) {
                                    $(child).slideUp('slow');
                                    $(child).remove();
                                }

                            }

                        });

                        if (nchildren.length) {

                            if (!children.length) {
                                el.children('div.children').remove();
                                var c = $('<div class="children"><div class="elements"><ol></ol></div></div>');
                                el.append(c);
                            }

                            nchildren.hide();
                            el.children('div.children').children('div.elements').children('ol').append(nchildren);
                            nchildren.slideDown('slow');                                    
                        }
                        */
                        
                        try {
                        
                            var parents = el.parentsUntil('.updating', 'div.superform, li.element');
                            parents.addClass('updating');

                            el.triggerHandler('sfafterupdate', [el, html]);

                            parents.each(function (i, e) {
                                $e = $(e);              
                                $e.triggerHandler('sfafterupdate', [$e, html]);
                            });
                            
                        } catch (e) {}
                        
                        if (nel) {
                            el.attr('class', nel.attr('class'));                            
                        }
                        
                        throw new Exception;
                        
                            
                    } // beforeupdate.isDefaultPrevented()


                } catch (e) {
                
                }
            },

            update: function (el, params, success) {
            
                if (typeof el === 'string') {
                    el = $('div.element#' + el);
                } else {                                                    
                    el = $(el);
                }

                el.is('li.element') || (el = el.parents('li.element').eq(0));
                                                                                                
                if (el.length) {
                
                    if (el.__updating) {
                        el.__updating.abort();
                    }                    

                    var frm = $(el).parents('form').eq(0);
                    
                    if (frm) {
                    
                        var id = el.attr('id');
                        
                        var data = frm.serializeArray();
                        
                        if (params) {
                            $.each(params, function (k, v) {
                                data.push({
                                    name: k,
                                    value: v
                                });
                            });
                        }         
                        
                        
                        el.__updating = $.ajax({
                            type:       'POST',
                            url:        frm.attr('action'),
                            cache:      false,
                            data:       data,                            
                            success:    function (html, status, xhr) {                        
                                            Superform.updateElement(el, null, html);
                                            sf.removeClass('updating');
                                            sf.find('li.element').removeClass('updating busy');
                                        },
                            error: function () {
                                /*alert('Error -->' + frm.attr('action') + '<--');*/
                            }
                        }); // el.__updating = $.ajax();
                    }

                }
            }

        };                
        
        var cfb = false;
        
        sf.delegate('li.element', 'click focusin', function (event) {
        
            $(event.target).parents('li.element').each(function (i, li) {
            
                var fb = $(li).find('div.feedback#superform-feedback-for-' + li.id).not(':empty').first();
                                                                
                if (fb.length) {
                
                    setTimeout(function () {
                        sf.find('div.feedback').not(fb).fadeOut(200);
                    });                                                                                                
                                    
                    setTimeout(function () {
                        fb.fadeIn(200);
                    });
                    
                    return false;
                }
                
            });            
            
        });                
        
    }); 
    
}