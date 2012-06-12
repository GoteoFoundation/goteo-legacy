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

use Goteo\Core\View,
    Goteo\Library\Text;

$user    = $_SESSION['user'];
$project = $this['project'];
$level = (int) $this['level'] ?: 3;

$lsuf = (LANG != 'es') ? '?lang='.LANG : '';

$url = SITE_URL . '/widget/project/' . $project->id;
$widget_code = Text::widget($url . $lsuf);
$widget_code_investor = Text::widget($url.'/invested/'.$user->id.'/'.$lsuf);
?>
<div class="widget project-spread">
    
    <h<?php echo $level ?> class="title"><?php echo Text::get('project-spread-header'); ?></h<?php echo $level ?>>
    
    <div class="widget projects">
   		 
          <div class="left">
              <div class="subtitle" id="s1">
                <span class="primero"><?php echo Text::get('project-spread-pre_widget')?></span>
                <span class="segundo"><?php echo Text::get('project-spread-widget')?></span>        
              </div>
         	             
              <div>
			  <?php
        
                    // el proyecto de trabajo
                    echo new View('view/project/widget/project.html.php', array(
                    'project'   => $project));
                ?>
              </div>
              
              <div id="widget-code">
                <div class="wc-embed" onclick="$('#widget_code').focus();$('#widget_code').select()"><?php echo Text::get('project-spread-embed_code'); ?></div>
                <textarea id="widget_code" onclick="this.focus();this.select()" readonly="readonly"><?php echo htmlentities($widget_code); ?></textarea>
              </div>
        
          </div>
            
          <div class="right">
             <div class="subtitle" id="s2">
                 <span class="primero"><?php echo Text::get('project-share-pre_header')?></span>
                 <span class="segundo"><?php echo Text::get('project-share-header')?></span>
	        </div>
            
	 
            <div>
				<?php
    
                    // el proyecto de trabajo
                    echo new View('view/project/widget/project.html.php', array(
                    'project'   => $project,
                    'investor'  => $user
                    ));
                ?>
            </div>
            
            <div>
                <div id="widget-code">
	                <div class="wc-embed" onclick="$('#investor_code').focus();$('#investor_code').select()"><?php echo Text::get('project-spread-embed_code'); ?></div>
              		<textarea id="investor_code" onclick="this.focus();this.select()" readonly="readonly"><?php echo htmlentities($widget_code_investor); ?></textarea>
    			</div>          
          
            
          </div>
          
   		 </div>
    </div>
        
</div>