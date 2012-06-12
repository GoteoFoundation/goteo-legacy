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
 function navi (item, cuantos) {

    if (item == '') item = 'image';

    $(".navi-"+item+"").removeClass('active');
    $("."+item+"").hide();

    $("#"+item+"-1").show();
    $("#navi-"+item+"-1").addClass('active');

    $(".navi-arrow-"+item).click(function (event) {
        event.preventDefault();

        /* Quitar todos los active, ocultar todos los elementos */
        $(".navi-"+item+"").removeClass('active');
        $("."+item+"").hide();
        /* Poner acctive a este, mostrar este */
        $("#navi-"+item+"-"+this.rel).addClass('active');
        $("#"+item+"-"+this.rel).show();

        var prev;
        var next;

        if (this.id == item+'-navi-next') {
            prev = parseFloat($("#"+item+"-navi-prev").attr('rel')) - 1;
            next = parseFloat($("#"+item+"-navi-next").attr('rel')) + 1;
        } else {
            prev = parseFloat(this.rel) - 1;
            next = parseFloat(this.rel);
        }

        if (prev < 1) {
            prev = cuantos;
        }

        if (next > cuantos) {
            next = 1;
        }

        if (next < 1) {
            next = cuantos;
        }

        if (prev > cuantos) {
            prev = 1;
        }

        $("#"+item+"-navi-prev").attr('rel', prev);
        $("#"+item+"-navi-next").attr('rel', next);
    });

    $(".navi-"+item).click(function (event) {
        event.preventDefault();

        /* Quitar todos los active, ocultar todos los elementos */
        $(".navi-"+item).removeClass('active');
        $("."+item).hide();
        /* Poner acctive a este, mostrar este*/
        $(this).addClass('active');
        $("#"+this.rel).show();
    });
}
