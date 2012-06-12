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

use Goteo\Library\Text,
    Goteo\Library\SuperForm,
    Goteo\Core\View;

$project = $this['project'];
$errors = $project->errors[$this['step']] ?: array();         
$okeys  = $project->okeys[$this['step']] ?: array();

// si tiene algo en direccion postal entonces tiene una direccion secundaria (la postal)
$secondary_address = empty($project->post_address) ? false : true;


echo new SuperForm(array(

    'level'         => $this['level'],
    'method'        => 'post',
    'title'         => Text::get('personal-main-header'),
    'hint'          => Text::get('guide-project-contract-information'),    
    'elements'      => array(
        'process_userPersonal' => array (
            'type' => 'hidden',
            'value' => 'userPersonal'
        ),
        
        /* Radio Tipo de persona */
        'contract_entity-radioset' => array(
            'type'      => 'group',
            'title'     => Text::get('personal-field-contract_entity'),
            'hint'      => Text::get('tooltip-project-contract_entity'),
            'children'  => array(
                'contract_entity-person' =>  array(
                    'name'  => 'contract_entity',
                    'value' => false,
                    'type'  => 'radio',
                    'class' => 'inline',
                    'label' => Text::get('personal-field-contract_entity-person'),
                    'id'    => 'contract_entity-person',
                    'checked' => !$project->contract_entity ? true : false,
                    'children' => array(
                        /* vacio si es persona f�sica */
                        'contract_entity-person' => array(
                            'type' => 'hidden',
                            'name' => "post_address-same",
                            'value' => 'person'
                        ),
                    )
                ),
                'contract_entity-entity' =>  array(
                    'name'  => 'contract_entity',
                    'value' => true,
                    'type'  => 'radio',
                    'class' => 'inline',
                    'label' => Text::get('personal-field-contract_entity-entity'),
                    'id'    => 'contract_entity-entity',
                    'checked' => $project->contract_entity ? true : false,
                    'children' => array(
                        /* A desplegar si es persona jur�dica */
                        'entity_name' => array(
                            'type'      => 'textbox',
                            'class'     => 'inline',
                            'required'  => true,
                            'size'      => 20,
                            'title'     => Text::get('personal-field-entity_name'),
                            'hint'      => Text::get('tooltip-project-entity_name'),
                            'errors'    => !empty($errors['entity_name']) ? array($errors['entity_name']) : array(),
                            'ok'        => !empty($okeys['entity_name']) ? array($okeys['entity_name']) : array(),
                            'value'     => $project->entity_name
                        ),
                        
                        'entity_cif' => array(
                            'type'      => 'textbox',
                            'class'     => 'inline',
                            'required'  => true,
                            'title'     => Text::get('personal-field-entity_cif'),
                            'size'      => 15,
                            'hint'      => Text::get('tooltip-project-entity_cif'),
                            'errors'    => !empty($errors['entity_cif']) ? array($errors['entity_cif']) : array(),
                            'ok'        => !empty($okeys['entity_cif']) ? array($okeys['entity_cif']) : array(),
                            'value'     => $project->entity_cif
                        ),
                        
                        'entity_office' => array(
                            'type'      => 'textbox',
                            'class'     => 'inline',
                            'required'  => true,
                            'size'      => 20,
                            'title'     => Text::get('personal-field-entity_office'),
                            'hint'      => Text::get('tooltip-project-entity_office'),
                            'errors'    => !empty($errors['entity_office']) ? array($errors['entity_office']) : array(),
                            'ok'        => !empty($okeys['entity_office']) ? array($okeys['entity_office']) : array(),
                            'value'     => $project->entity_office
                        )
                    )
                )
            )
        ),

        'contract' => array(
            'type'      => 'group',
            'title'     => Text::get('personal-field-contract_data'),
            'hint'      => Text::get('tooltip-project-contract_data'),
            'children'  => array(
                'contract_name' => array(
                    'type'      => 'textbox',
                    'class'     => 'inline',
                    'required'  => true,
                    'size'      => 20,
                    'title'     => Text::get('personal-field-contract_name'),
                    'hint'      => Text::get('tooltip-project-contract_name'),
                    'errors'    => !empty($errors['contract_name']) ? array($errors['contract_name']) : array(),
                    'ok'        => !empty($okeys['contract_name']) ? array($okeys['contract_name']) : array(),
                    'value'     => $project->contract_name
                ),

                'contract_nif' => array(
                    'type'      => 'textbox',
                    'class'     => 'inline',
                    'required'  => true,
                    'title'     => Text::get('personal-field-contract_nif'),
                    'size'      => 9,
                    'hint'      => Text::get('tooltip-project-contract_nif'),
                    'errors'    => !empty($errors['contract_nif']) ? array($errors['contract_nif']) : array(),
                    'ok'        => !empty($okeys['contract_nif']) ? array($okeys['contract_nif']) : array(),
                    'value'     => $project->contract_nif
                ),

                'phone' => array(
                    'type'      => 'textbox',
                    'class'     => 'inline',
                    'required'  => true,
                    'title'     => Text::get('personal-field-phone'),
                    'dize'      => 15,
                    'hint'      => Text::get('tooltip-project-phone'),
                    'errors'    => !empty($errors['phone']) ? array($errors['phone']) : array(),
                    'ok'        => !empty($okeys['phone']) ? array($okeys['phone']) : array(),
                    'value'     => $project->phone
                ),

                'contract_email' => array(
                    'type'      => 'textbox',
                    'class'     => 'inline',
                    'required'  => true,
                    'title'     => Text::get('personal-field-contract_email'),
                    'size'      => 9,
                    'hint'      => Text::get('tooltip-project-contract_email'),
                    'errors'    => !empty($errors['contract_email']) ? array($errors['contract_email']) : array(),
                    'ok'        => !empty($okeys['contract_email']) ? array($okeys['contract_email']) : array(),
                    'value'     => $project->contract_email
                ),

                'contract_birthdate'  => array(
                    'type'      => 'datebox',
                    'required'  => true,
                    'size'      => 8,
                    'title'     => Text::get('personal-field-contract_birthdate'),
                    'hint'      => Text::get('tooltip-project-contract_birthdate'),
                    'errors'    => !empty($errors['contract_birthdate']) ? array($errors['contract_birthdate']) : array(),
                    'ok'        => !empty($okeys['contract_birthdate']) ? array($okeys['contract_birthdate']) : array(),
                    'value'     => $project->contract_birthdate
                )

            )
        ),

        /* Domicilio */
        'main_address' => array(
            'type'      => 'group',
            'title'     => Text::get('personal-field-main_address'),
            'hint'      => Text::get('tooltip-project-main_address'),
            'children'  => array(
                'address' => array(
                    'type'      => 'textbox',
                    'class'     => 'inline',
                    'required'  => true,
                    'title'     => Text::get('personal-field-address'),
                    'rows'      => 6,
                    'cols'      => 40,
                    'hint'      => Text::get('tooltip-project-main_address'),
                    'errors'    => !empty($errors['address']) ? array($errors['address']) : array(),
                    'ok'        => !empty($okeys['address']) ? array($okeys['address']) : array(),
                    'value'     => $project->address
                ),

                'zipcode' => array(
                    'type'      => 'textbox',
                    'class'     => 'inline',
                    'required'  => true,
                    'title'     => Text::get('personal-field-zipcode'),
                    'size'      => 7,
                    'hint'      => Text::get('tooltip-project-main_address'),
                    'errors'    => !empty($errors['zipcode']) ? array($errors['zipcode']) : array(),
                    'ok'        => !empty($okeys['zipcode']) ? array($okeys['zipcode']) : array(),
                    'value'     => $project->zipcode
                ),

                'location' => array(
                    'type'      => 'textbox',
                    'class'     => 'inline',
                    'required'  => true,
                    'title'     => Text::get('personal-field-location'),
                    'size'      => 25,
                    'hint'      => Text::get('tooltip-project-main_address'),
                    'errors'    => !empty($errors['location']) ? array($errors['location']) : array(),
                    'ok'        => !empty($okeys['location']) ? array($okeys['location']) : array(),
                    'value'     => $project->location
                ),

                'country' => array(
                    'type'      => 'textbox',
                    'class'     => 'inline',
                    'required'  => true,
                    'title'     => Text::get('personal-field-country'),
                    'size'      => 25,
                    'hint'      => Text::get('tooltip-project-main_address'),
                    'errors'    => !empty($errors['country']) ? array($errors['country']) : array(),
                    'ok'        => !empty($okeys['country']) ? array($okeys['country']) : array(),
                    'value'     => $project->country
                )
            )
        ),

        /* Radio de domicilio postal igual o diferente*/
        'post_address-radioset' => array(
            'type'      => 'group',
            'class'     => 'inline',
            'title'     => Text::get('personal-field-post_address'),
            'hint'      => Text::get('tooltip-project-post_address'),
            'children'  => array(
                'post_address-radio-same' =>  array(
                    'name'  => 'secondary_address',
                    'value' => false,
                    'type'  => 'radio',
                    'class' => 'inline',
                    'label' => Text::get('personal-field-post_address-same'),
                    'id'    => 'post_address-radio-same',
                    'checked' => !$project->secondary_address ? true : false,
                    'children' => array(
                        /* Children vacio si es igual */
                        'post_address-same' => array(
                            'type' => 'hidden',
                            'name' => "post_address-same",
                            'value' => 'same'
                        ),
                    )
                ),
                'post_address-radio-different' =>  array(
                    'name'  => 'secondary_address',
                    'value' => true,
                    'type'  => 'radio',
                    'class' => 'inline',
                    'label' => Text::get('personal-field-post_address-different'),
                    'id'    => 'post_address-radio-different',
                    'checked' => $project->secondary_address ? true : false,
                    'children' => array(
                        /* Domicilio postal (a desplegar si es diferente) */
                        'post_address' => array(
                            'type'      => 'textbox',
                            'class'     => 'inline',
                            'title'     => Text::get('personal-field-address'),
                            'rows'      => 6,
                            'cols'      => 40,
                            'hint'      => Text::get('tooltip-project-post_address'),
                            'errors'    => !empty($errors['post_address']) ? array($errors['post_address']) : array(),
                            'ok'        => !empty($okeys['post_address']) ? array($okeys['post_address']) : array(),
                            'value'     => $project->post_address
                        ),

                        'post_zipcode' => array(
                            'type'      => 'textbox',
                            'class'     => 'inline',
                            'title'     => Text::get('personal-field-zipcode'),
                            'size'      => 7,
                            'hint'      => Text::get('tooltip-project-post_address'),
                            'errors'    => !empty($errors['post_zipcode']) ? array($errors['post_zipcode']) : array(),
                            'ok'        => !empty($okeys['post_zipcode']) ? array($okeys['post_zipcode']) : array(),
                            'value'     => $project->post_zipcode
                        ),

                        'post_location' => array(
                            'type'      => 'textbox',
                            'class'     => 'inline',
                            'title'     => Text::get('personal-field-location'),
                            'size'      => 25,
                            'hint'      => Text::get('tooltip-project-post_address'),
                            'errors'    => !empty($errors['post_location']) ? array($errors['post_location']) : array(),
                            'ok'        => !empty($okeys['post_location']) ? array($okeys['post_location']) : array(),
                            'value'     => $project->post_location
                        ),

                        'post_country' => array(
                            'type'      => 'textbox',
                            'class'     => 'inline',
                            'title'     => Text::get('personal-field-country'),
                            'size'      => 25,
                            'hint'      => Text::get('tooltip-project-post_address'),
                            'errors'    => !empty($errors['post_country']) ? array($errors['post_country']) : array(),
                            'ok'        => !empty($okeys['post_country']) ? array($okeys['post_country']) : array(),
                            'value'     => $project->post_country
                        )
                    )
                ),
            )
        ),

        'footer' => array(
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
                    'children' => array(
                        'next' => array(
                            'type'  => 'submit',
                            'name'  => 'view-step-overview',
                            'label' => Text::get('form-next-button'),
                            'class' => 'next'
                        )
                    )
                )
            )
        
        )
        
    )

));