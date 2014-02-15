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
$errors = $this['errors'];

$original = \Goteo\Model\Project::get($project->id);

// media del proyecto
if (!empty($project->media->url)) {
    $media = array(
            'type'  => 'media',
            'title' => Text::get('overview-field-media_preview'),
            'class' => 'inline media',
            'type'  => 'html',
            'html'  => !empty($project->media) ? $project->media->getEmbedCode() : ''
    );
} else {
    $media = array(
        'type'  => 'hidden',
        'class' => 'inline'
    );
}

// video de motivacion
if (!empty($project->video->url)) {
    $video = array(
            'type'  => 'media',
            'title' => Text::get('overview-field-media_preview'),
            'class' => 'inline media',
            'type'  => 'html',
            'html'  => !empty($project->video) ? $project->video->getEmbedCode() : ''
    );
} else {
    $video = array(
        'type'  => 'hidden',
        'class' => 'inline'
    );
}



?>

<form method="post" action="/dashboard/translates/overview/save" class="project" enctype="multipart/form-data">

<?php echo new SuperForm(array(
    'level'         => 3,
    'action'        => '',
    'method'        => 'post',
    'title'         => '',
    'hint'          => Text::get('guide-project-description'),
    'class'         => 'aqua',
    'footer'        => array(
        'view-step-preview' => array(
            'type'  => 'submit',
            'name'  => 'save-overview',
            'label' => Text::get('regular-save'),
            'class' => 'next'
        )
    ),
    'elements'      => array(
        'process_overview' => array (
            'type' => 'hidden',
            'value' => 'overview'
        ),

        /*
        'name' => array(
            'type'      => 'textbox',
            'title'     => Text::get('overview-field-name'),
            'required'  => true,
            'hint'      => Text::get('tooltip-project-name'),
            'value'     => $project->name,
            'errors'    => !empty($errors['name']) ? array($errors['name']) : array(),
            'ok'        => !empty($okeys['name']) ? array($okeys['name']) : array()
        ),
        */

        'subtitle-orig' => array(
            'type'      => 'html',
            'title'     => Text::get('overview-field-subtitle'),
            'html'     => $original->subtitle
        ),
        'subtitle' => array(
            'type'      => 'textbox',
            'title'     => '',
            'class'     => 'inline',
            'value'     => $project->subtitle,
            'hint'      => Text::get('tooltip-project-subtitle'),
            'errors'    => array(),
            'ok'        => array()
        ),

        'description-orig' => array(
            'type'      => 'html',
            'title'     => Text::get('overview-field-description'),
            'html'     => nl2br($original->description)
        ),
        'description' => array(
            'type'      => 'textarea',
            'title'     => '',
            'class'     => 'inline',
            'hint'      => Text::get('tooltip-project-description'),
            'value'     => $project->description,
            'errors'    => array(),
            'ok'        => array()
        ),
        'description_group' => array(
            'type' => 'group',
            'children'  => array(
                'about-orig' => array(
                    'type'      => 'html',
                    'title'     => Text::get('overview-field-about'),
                    'html'     => $original->about
                ),
                'about' => array(
                    'type'      => 'textarea',
                    'title'     => '',
                    'class'     => 'inline',
                    'hint'      => Text::get('tooltip-project-about'),
                    'errors'    => array(),
                    'ok'        => array(),
                    'value'     => $project->about
                ),
                'motivation-orig' => array(
                    'type'      => 'html',
                    'title'     => Text::get('overview-field-motivation'),
                    'html'     => nl2br($original->motivation)
                ),
                'motivation' => array(
                    'type'      => 'textarea',
                    'title'     => '',
                    'class'     => 'inline',
                    'hint'      => Text::get('tooltip-project-motivation'),
                    'errors'    => array(),
                    'ok'        => array(),
                    'value'     => $project->motivation
                ),
                // video motivacion
                'video-orig' => array(
                    'type'      => 'html',
                    'title'     => Text::get('overview-field-video'),
                    'html'     => (string) $original->video->url
                ),

                'video' => array(
                    'type'      => 'textbox',
                    'hint'      => Text::get('tooltip-project-video'),
                    'errors'    => array(),
                    'ok'        => array(),
                    'value'     => (string) $project->video
                ),

                'video-upload' => array(
                    'name' => "upload",
                    'type'  => 'submit',
                    'label' => Text::get('form-upload-button'),
                    'class' => 'inline media-upload'
                ),

                'video-preview' => $video
                ,
                // fin video motivacion
                'goal-orig' => array(
                    'type'      => 'html',
                    'title'     => Text::get('overview-field-goal'),
                    'html'     => nl2br($original->goal)
                ),
                'goal' => array(
                    'type'      => 'textarea',
                    'title'     => '',
                    'class'     => 'inline',
                    'hint'      => Text::get('tooltip-project-goal'),
                    'errors'    => array(),
                    'ok'        => array(),
                    'value'     => $project->goal
                ),
                'related-orig' => array(
                    'type'      => 'html',
                    'title'     => Text::get('overview-field-related'),
                    'html'     => nl2br($original->related)
                ),
                'related' => array(
                    'type'      => 'textarea',
                    'title'     => '',
                    'class'     => 'inline',
                    'hint'      => Text::get('tooltip-project-related'),
                    'errors'    => array(),
                    'ok'        => array(),
                    'value'     => $project->related
                ),
                'reward-orig' => array(
                    'type'      => 'html',
                    'title'     => Text::get('overview-field-reward'),
                    'html'     => nl2br($original->reward)
                ),
                'reward' => array(
                    'type'      => 'textarea',
                    'title'     => '',
                    'class'     => 'inline',
                    'hint'      => Text::get('tooltip-project-reward'),
                    'errors'    => array(),
                    'ok'        => array(),
                    'value'     => $project->reward
                )
            )
        ),

        'keywords-orig' => array(
            'type'      => 'html',
            'title'     => Text::get('overview-field-keywords'),
            'html'     => $original->keywords
        ),
        'keywords' => array(
            'type'      => 'textbox',
            'title'     => '',
            'class'     => 'inline',
            'hint'      => Text::get('tooltip-project-keywords'),
            'errors'    => array(),
            'ok'        => array(),
            'value'     => $project->keywords
        ),

        'media-orig' => array(
            'type'      => 'html',
            'title'     => Text::get('overview-field-media'),
            'html'     => (string) $original->media->url
        ),

        'media' => array(
            'type'      => 'textbox',
            'title'     => '',
            'class'     => 'inline',
            'hint'      => Text::get('tooltip-project-media'),
            'errors'    => array(),
            'ok'        => array(),
            'value'     => (string) $project->media
        ),

        'media-upload' => array(
            'name' => "upload",
            'type'  => 'submit',
            'label' => Text::get('form-upload-button'),
            'class' => 'inline media-upload'
        ),

        'media-preview' => $media

    )

));
?>
</form>