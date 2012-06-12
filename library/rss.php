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

namespace Goteo\Library {

    require_once 'library/rss/FeedWriter.php';  // Libreria para creacion de rss

	/*
	 * Clase para usar La libreria FeedWriter
	 */
    class Rss {

        public static function get($config, $data, $gformat = null) {
            
            $feed = new \FeedWriter(
                                $config['title'],
                                $config['description'],
                                $config['link'],
                                $config['indent'],
                                true,
                                null,
                                true
                    );

            // debug
            $feed->debug = true;

            //format
            $format = \RSS_2_0;
            if (isset($gformat)){
                foreach ($feed->getFeedFormats() as $cFormat) {
                    if ($cFormat[0] == $gformat) {
                        $format = $cFormat[1];
                    }

                }
            }

            //channel
//            $feed->set_image('Goteo.org', SITE_URL . '/images/logo.jpg');
            $feed->set_language('ES-ES'); // segun \LANG
            $feed->set_date(\date('Y-m-d\TH:i:s').'Z', DATE_UPDATED);
            $feed->set_author(null, 'Goteo');
            $feed->set_selfLink(SITE_URL . '/rss');

            foreach ($data['tags'] as $tagId => $tagName) {
                $feed->add_category($tagName);
            }


            date_default_timezone_set('UTC');

            foreach ($data['posts'] as $postId=>$post) {

                // fecha
                $postDate = explode('-', $post->date);
                $date = \mktime(0, 0, 0, $postDate[1], $postDate[0], $postDate[2]);

                //item $postId
                $feed->add_item($post->title, $post->text, SITE_URL . '/blog/' . $post->id);
                $feed->set_date(\date(DATE_ATOM, $date), DATE_PUBLISHED);

                foreach ($post->tags as $tagId => $tagName) {
                    $feed->add_category($tagName);
                }

                // html output
                $feed->set_feedConstruct($format);
                $feed->feed_construct->construct['itemTitle']['type'] = 'html';
                $feed->feed_construct->construct['itemContent']['type'] = 'html';
            }

            return $feed->getXML($format);
        }

	}
	
}