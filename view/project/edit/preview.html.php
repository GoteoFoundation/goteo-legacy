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
    Goteo\Library\Text,
    Goteo\Library\SuperForm;

$project = $this['project'];
$types   = $this['types'];
$errors = $project->errors ?: array();

// miramos el pruimer paso con errores para mandarlo a ese
$goto = 'view-step-userProfile';
foreach ($this['steps'] as $id => $data) {

    if (empty($step) && !empty($project->errors[$id])) {
        $goto = 'view-step-' . $id;
        break;
    }
}

// boton de revisar que no sirve para mucho
$buttons = array(
    'review' => array(
        'type'  => 'submit',
        'name'  => $goto,
        'label' => Text::get('form-self_review-button'),
        'class' => 'retry'
    )
);

// si es enviable ponemos el boton
if ($project->finishable) {
    $buttons['finish'] = array(
        'type'  => 'submit',
        'name'  => 'finish',
        'label' => Text::get('form-send_review-button'),
        'class' => 'confirm red'
    );
} else {
    $buttons['nofinish'] = array(
        'type'  => 'submit',
        'name'  => 'nofinish',
        'label' => Text::get('form-send_review-button'),
        'class' => 'confirm disabled',
        'disabled' => 'disabled'
    );
}

// elementos generales de preview
$elements      = array(
    'process_preview' => array (
        'type' => 'hidden',
        'value' => 'preview'
    ),

    'preview' => array(
        'type'      => 'html',
        'class'     => 'fullwidth',
        'html'      =>   '<div class="project-preview" style="position: relative"><div>'
                       . '<div class="overlay" style="position: absolute; left: 0; top: 0; right: 0; bottom: 0; z-index: 999"></div>'
                       . '<div style="z-index: 0">'
                       . new View('view/project/widget/support.html.php', array('project' => $project))
                       . new View('view/project/widget/collaborations.html.php', array('project' => $project))
                       . new View('view/project/widget/rewards.html.php', array('project' => $project))
                       . new View('view/user/widget/user.html.php', array('user' => $project->user))
                       . new View('view/project/widget/media.html.php', array('project' => $project))
                       . new View('view/project/widget/share.html.php', array('project' => $project))
                       . new View('view/project/widget/summary.html.php', array('project' => $project))
                       . new View('view/project/widget/needs.html.php', array('project' => $project, 'types' => $types))
                       . new View('view/project/widget/schedule.html.php', array('project' => $project))
                       . '</div>'
                       . '</div></div>'
    )
);

// si es enviable ponemos el campo de comentario
if ($project->finishable) {
    $elements['comment'] = array(
            'type'  =>'textarea',
            'title' => Text::get('preview-send-comment'),
            'rows'  => 8,
            'cols'  => 100,
            'hint'  => Text::get('tooltip-project-comment'),
            'value' => $project->comment
        );
}

// Footer
$elements['footer'] = array(
    'type'      => 'group',
    'children'  => array(
        'errors' => array(
            'title' => Text::get('form-footer-errors_title'),
            'view'  => new View('view/project/edit/errors.html.php', array(
                'project'   => $project,
                'step'      => $this['step']
            ))                    
        ),
        'buttons'  => array(
            'type'  => 'group',
            'children' => $buttons
        )
    )

);

// lanzamos el superform
echo new SuperForm(array(
    'action'        => '',
    'level'         => $this['level'],
    'method'        => 'post',
    'title'         => Text::get('preview-main-header'),
    'hint'          => Text::get('guide-project-preview'),
    'elements'      => $elements
));