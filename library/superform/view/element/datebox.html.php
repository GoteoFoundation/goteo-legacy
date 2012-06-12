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
<input name="<?php echo htmlspecialchars($this['name']) ?>" type="text"<?php if (isset($this['class'])) echo ' class="' . htmlspecialchars($this['class']) . '"'?>  value="<?php if (isset($this['value'])) echo htmlspecialchars($this['value']) ?>"<?php if (isset($this['size'])) echo 'size="' . ((int) $this['size']) . '"' ?> />
<script type="text/javascript" src="<?php echo SRC_URL ?>/view/js/datepicker.min.js"></script>
<script type="text/javascript">
    
    (function () {
    
        var dp = $('#<?php echo $this['id'] ?> input');

        dp.DatePicker({           
            format: 'Y-m-d',
            date: '<?php echo $this['value'] ?>',
            current: '<?php echo $this['value'] ?>',
            starts: 1,
            position: 'bottom',      
            eventName: 'click',
            onBeforeShow: function(){
                dp.DatePickerSetDate(dp.val(), true);                
            },
            onChange: function(formatted, dates){                    
                    dp.val(formatted);
                    dp.DatePickerHide();
                    dp.focus();
            },
            locale: {
                days: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábad', 'Domingo'],
                daysShort: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                daysMin: ['L', 'M', 'X', 'J', 'V', 'S', 'D'],
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                week: []
            }
        });                
               
    })();
</script>

