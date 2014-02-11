<?php
namespace Goteo\Library {

	use Goteo\Core\Model,
        Goteo\Core\Exception;

	/*
	 * Clase para obtener datos para distintos reports
     *
	 */
    class Reporting {

            static public $reports = array(
                'money' => array(
                    'label' => 'Dinero',
                    'values' => array(
                        array(
                            'label' => 'Dinero comprometido',
                            'sql'   => "SELECT SUM(invest.amount)
                                        FROM invest
                                        WHERE invest.status IN (0, 1, 3, 4)
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Dinero devuelto (archivado)',
                            'sql'   => "SELECT SUM(invest.amount)
                                        FROM invest
                                        WHERE invest.status = 4
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Perc. del comprometido que se ha devuelto',
                            'sql'   => "SELECT 	(
                                                SELECT SUM(invest.amount)
                                                FROM invest
                                                WHERE invest.status = 4
                                            ) / (
                                                SELECT SUM(invest.amount)
                                                FROM invest
                                                WHERE invest.status IN (0, 1, 3, 4)
                                            ) * 100 as percent
                                        FROM DUAL
                                        ",
                            'unit'  => '%',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Dinero cancelado (por incidencia)',
                            'sql'   => "SELECT SUM(invest.amount)
                                        FROM invest
                                        WHERE invest.status = 2
                                        AND invest.issue IS NOT NULL
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Dinero cancelado (no incidencia)',
                            'sql'   => "SELECT SUM(invest.amount)
                                        FROM invest
                                        WHERE invest.status = 2
                                        AND invest.issue IS NULL
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Dinero recaudado',
                            'sql'   => "SELECT SUM(invest.amount)
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (4, 5)
                                        WHERE invest.status IN (1, 3)
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Recaudado mediante PayPal',
                            'sql'   => "SELECT SUM(invest.amount)
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (4, 5)
                                        WHERE invest.status IN (1, 3)
                                        AND invest.method = 'paypal'
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Recaudado mediante TPV',
                            'sql'   => "SELECT SUM(invest.amount)
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (4, 5)
                                        WHERE invest.status IN (1, 3)
                                        AND invest.method = 'tpv'
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Recaudado mediante aportes manuales',
                            'sql'   => "SELECT SUM(invest.amount)
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (4, 5)
                                        WHERE invest.status IN (1, 3)
                                        AND invest.method = 'cash'
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Total 8% recaudado por Goteo',
                            'sql'   => "SELECT SUM(invest.amount) * 0.08
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (4, 5)
                                        WHERE invest.status IN (1, 3)
                                        ",
                            'unit'  => '€',
                            'format'=> 'amount'
                        ),
                        array(
                            'label' => 'Aporte medio por cofinanciador',
                            'sql'   => "SELECT SUM(invest.amount) / COUNT(invest.id)
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (4, 5)
                                        WHERE invest.status IN (1, 3)
                                        ",
                            'unit'  => ' €/cof.',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Aporte medio por cofinanciador mediante PayPal',
                            'sql'   => "SELECT SUM(invest.amount) / COUNT(invest.id)
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (4, 5)
                                        WHERE invest.status IN (1, 3)
                                        AND invest.method = 'paypal'
                                        ",
                            'unit'  => ' €/cof.',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Coste mínimo medio por proyecto exitoso',
                            'sql'   => "SELECT SUM(cost.amount) / COUNT(DISTINCT(project.id))
                                        FROM cost
                                        INNER JOIN project
                                            ON  project.id = cost.project
                                            AND project.status IN (3, 4, 5, 6)
                                        WHERE cost.required = 1
                                        ",
                            'unit'  => ' €/proy.',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Recaudación media por proyecto exitoso',
                            'sql'   => "SELECT SUM(invest.amount) / COUNT(DISTINCT(project.id))
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (4, 5)
                                        WHERE invest.status IN (1, 3)
                                        ",
                            'unit'  => ' €/proy.',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Perc. medio de recaudacion sobre mínimo',
                            'sql'   => "SELECT (
                                                SELECT SUM(invest.amount)
                                                FROM invest
                                                WHERE invest.status IN (1, 3)
                                                AND invest.project = project.id
                                            ) / (
                                                SELECT SUM(cost.amount)
                                                FROM cost
                                                WHERE cost.required = 1
                                                AND cost.project = project.id
                                            ) * 100 as percent
                                        FROM project
                                        WHERE project.status IN (4, 5)
                                        ",
                            'unit'  => '%',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Dinero compr. medio por proyecto archivado',
                            'sql'   => "SELECT SUM(invest.amount) / COUNT(DISTINCT(project.id))
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.id = invest.project
                                            AND project.status IN (6)
                                        WHERE invest.status IN (0, 4)
                                        ",
                            'unit'  => ' €/proy.',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Perc. dinero compr. medio sobre mínimo',
                            'sql'   => "SELECT (
                                                SELECT SUM(invest.amount)
                                                FROM invest
                                                WHERE invest.status IN (0, 4)
                                                AND invest.project = project.id
                                            ) / (
                                                SELECT SUM(cost.amount)
                                                FROM cost
                                                WHERE cost.required = 1
                                                AND cost.project = project.id
                                            ) * 100 as percent
                                        FROM project
                                        WHERE project.status IN (6)
                                        ",
                            'unit'  => '%',
                            'format'=> '2dec'
                        )
                    )
                ),
                'community' => array(
                    'label' => 'Comunidad',
                    'values' => array(
                        array(
                            'label' => 'Número total de usuarios',
                            'sql'   => "SELECT COUNT(id)
                                        FROM user
                                        "
                        ),
                        array(
                            'label' => 'Número de bajas',
                            'sql'   => "SELECT COUNT(id)
                                        FROM user
                                        WHERE active = 0
                                        "
                        ),
                        array(
                            'label' => 'Número de cofinanciadores',
                            'sql'   => "SELECT COUNT(DISTINCT(invest.user))
                                        FROM invest
                                        WHERE invest.status IN (0, 1, 3, 4)
                                        "
                        ),
                        array(
                            'label' => 'Cofinanciadores que colaboran',
                            'sql'   => "SELECT COUNT(DISTINCT(invest.user))
                                        FROM invest
                                        INNER JOIN message
                                            ON  message.user = invest.user
                                            AND thread > 0
                                            AND thread IN (
                                                SELECT id FROM message WHERE `blocked` = 1
                                            )
                                        WHERE invest.status IN (0, 1, 3, 4)
                                        "
                        ),
                        array(
                            'label' => 'Multi-Cofinanciadores (a más de 1 proyecto)',
                            'sql'   => "SELECT COUNT(*) FROM (
                                            SELECT  invest.user
                                            FROM invest
                                            WHERE invest.status IN (0, 1, 3, 4)
                                            GROUP BY invest.user
                                            HAVING COUNT(invest.id) > 1 AND COUNT(invest.project) >1
                                            ) as temp
                                        "
                        ),
                        array(
                            'label' => 'Cofinanciadores usando PayPal',
                            'sql'   => "SELECT COUNT(DISTINCT(invest.user))
                                        FROM invest
                                        WHERE invest.status IN (0, 1, 3, 4)
                                        AND invest.method = 'paypal'
                                        "
                        ),
                        array(
                            'label' => 'Multi-Cofinanciadores usando PayPal',
                            'sql'   => "SELECT COUNT(*) FROM (
                                            SELECT  invest.user
                                            FROM invest
                                            WHERE invest.status IN (0, 1, 3, 4)
                                            AND invest.method = 'paypal'
                                            GROUP BY invest.user
                                            HAVING COUNT(invest.id) > 1 AND COUNT(invest.project) >1
                                            ) as temp
                                        "
                        ),
                        array(
                            'label' => 'Número de colaboradores',
                            'sql'   => "SELECT COUNT(DISTINCT(message.user))
                                        FROM message
                                        WHERE thread > 0
                                        AND thread IN (
                                            SELECT id FROM message WHERE `blocked` = 1
                                        )
                                        "
                        ),
                        array(
                            'label' => 'Media de cofinanciadores por proyecto exitoso',
                            'sql'   => "SELECT (
                                                SELECT SUM(usr) 
                                                FROM (
                                                    SELECT COUNT(DISTINCT(invest.user)) as usr
                                                    FROM invest
                                                    INNER JOIN project
                                                        ON  project.id = invest.project
                                                        AND project.status IN (4, 5)
                                                    WHERE invest.status IN (1, 3)
                                                    GROUP BY invest.project
                                                    ) as temp1
                                            )
                                            / (
                                                SELECT COUNT(*) 
                                                FROM (
                                                    SELECT invest.project
                                                    FROM invest
                                                    INNER JOIN project
                                                        ON  project.id = invest.project
                                                        AND project.status IN (4, 5)
                                                    WHERE invest.status IN (1, 3)
                                                    GROUP BY invest.project
                                                    ) as numero_proyectos
                                            ) as average
                                        FROM dual		
                                        ",
                            'unit'  => 'cof./proy.',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Media de colaboradores por proyecto',
                            'sql'   => "SELECT (
                                                SELECT SUM(usr)
                                                FROM (
                                                    SELECT COUNT(DISTINCT(message.user)) as usr
                                                    FROM message
                                                    WHERE thread > 0
                                                    AND thread IN (
                                                        SELECT id FROM message WHERE `blocked` = 1
                                                    )
                                                    GROUP BY message.project
                                                    ) as temp1
                                            )
                                            / (
                                                SELECT COUNT(*)
                                                FROM (
                                                    SELECT message.project
                                                    FROM message
                                                    WHERE thread > 0
                                                    AND thread IN (
                                                        SELECT id FROM message WHERE `blocked` = 1
                                                    )
                                                    GROUP BY message.project
                                                    ) as numero_proyectos
                                            ) as average
                                        FROM dual
                                        ",
                            'unit'  => 'col./proy.',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => '1ª Categoría con más usuarios interesados',
                            'sql'   => "SELECT category.name
                                        FROM user_interest
                                        INNER JOIN category
                                            ON user_interest.interest = category. id
                                        GROUP BY user_interest.interest
                                        ORDER BY COUNT(user_interest.user) DESC
                                        LIMIT 1
                                        ",
                            'result'=> 'rows'
                        ),
                        array(
                            'label' => 'Porcentaje de usuarios en esta 1ª',
                            'sql'   => "SELECT COUNT(user_interest.user) / (SELECT COUNT(id) FROM user) * 100 as percent
                                        FROM user_interest
                                        INNER JOIN category
                                            ON user_interest.interest = category. id
                                        GROUP BY user_interest.interest
                                        ORDER BY COUNT(user_interest.user) DESC
                                        LIMIT 1
                                        ",
                            'unit'  => '%',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => '2ª Categoría con más usuarios interesados',
                            'sql'   => "SELECT category.name
                                        FROM user_interest
                                        INNER JOIN category
                                            ON user_interest.interest = category. id
                                        GROUP BY user_interest.interest
                                        ORDER BY COUNT(user_interest.user) DESC
                                        LIMIT 1
                                        OFFSET 1
                                        ",
                            'result'=> 'rows'
                        ),
                        array(
                            'label' => 'Porcentaje de usuarios en esta 2ª',
                            'sql'   => "SELECT COUNT(user_interest.user) / (SELECT COUNT(id) FROM user) * 100 as percent
                                        FROM user_interest
                                        INNER JOIN category
                                            ON user_interest.interest = category. id
                                        GROUP BY user_interest.interest
                                        ORDER BY COUNT(user_interest.user) DESC
                                        LIMIT 1
                                        OFFSET 1
                                        ",
                            'unit'  => '%',
                            'format'=> '2dec'
                        ),
                        array(
                            'label' => 'Núm. usuarios sin rellenar perfil',
                            'sql'   => "SELECT COUNT(user.id)
                                        FROM user
                                        WHERE ( location IS NULL     OR location = '')
                                        AND   ( about IS NULL        OR about = '')
                                        AND   ( keywords IS NULL     OR keywords = '')
                                        AND   ( avatar IS NULL       OR avatar = '')
                                        AND   ( contribution IS NULL OR contribution = '')
                                        AND   ( twitter IS NULL      OR twitter = '')
                                        AND   ( facebook IS NULL     OR facebook = '')
                                        "
//                                        AND   (SELECT COUNT(user_web.url) FROM user_web WHERE user_web.user = user.id) = 0
                        ),
                        array(
                            'label' => 'Núm. usuarios con algo rellenado',
                            'sql'   => "SELECT COUNT(user.id)
                                        FROM user
                                        WHERE ( location IS NOT NULL     AND location != '')
                                        OR    ( about IS NOT NULL        AND about != '')
                                        OR    ( keywords IS NOT NULL     AND keywords != '')
                                        OR    ( avatar IS NOT NULL       AND avatar != '')
                                        OR    ( contribution IS NOT NULL AND contribution != '')
                                        OR    ( twitter IS NOT NULL      AND twitter != '')
                                        OR    ( facebook IS NOT NULL     AND facebook != '')
                                        "
//                                        OR    (SELECT COUNT(user_web.url) FROM user_web WHERE user_web.user = user.id) > 0
                        ),
                        array(
                            'label' => 'Núm. impulsores que cofinancian a otros',
                            'sql'   => "SELECT COUNT(DISTINCT(invest.user))
                                        FROM invest
                                        INNER JOIN project
                                            ON  project.owner = invest.user
                                            AND project.status IN (3, 4, 5, 6)
                                        WHERE invest.status IN (0, 1, 3, 4)
                                        AND invest.project != project.id
                                        "
                        ),
                        array(
                            'label' => 'Núm. impulsores que colaboran con otros',
                            'sql'   => "SELECT COUNT(DISTINCT(message.user))
                                        FROM message
                                        INNER JOIN project
                                            ON  project.owner = message.user
                                            AND project.status IN (3, 4, 5, 6)
                                        WHERE thread > 0
                                        AND thread IN (
                                            SELECT id FROM message WHERE `blocked` = 1
                                        )
                                        AND message.project != project.id
                                        "
                        ),
                        array(
                            'label' => 'Media de posts proyecto exitoso',
                            'sql'   => "SELECT (
                                                SELECT SUM(posts) 
                                                FROM (
                                                    SELECT COUNT(post.id) as posts
                                                    FROM post
                                                    INNER JOIN blog
                                                        ON   blog.id = post.blog
                                                        AND  blog.type = 'project'
                                                    INNER JOIN project
                                                        ON  project.id = blog.owner
                                                        AND project.status IN (4, 5)
                                                    WHERE post.publish = 1
                                                    GROUP BY post.blog
                                                    ) as temp1
                                            )
                                            / (
                                                SELECT COUNT(*) 
                                                FROM (
                                                    SELECT project.id
                                                    FROM post
                                                    INNER JOIN blog
                                                        ON   blog.id = post.blog
                                                        AND  blog.type = 'project'
                                                    INNER JOIN project
                                                        ON  project.id = blog.owner
                                                        AND project.status IN (4, 5)
                                                    WHERE post.publish = 1
                                                    GROUP BY post.blog
                                                    ) as numero_proyectos
                                            ) as average
                                        FROM dual		
                                        ",
                            'unit' => 'post/proy.',
                            'format'=> '2dec'
                        )
                    )
                ),
                'projects' => array(
                    'label' => 'Proyectos',
                    'values' => array(
                        array(
                            'label' => 'Proyectos enviados a revisión',
                            'sql'   => "SELECT COUNT(project.id)
                                        FROM project
                                        WHERE (project.updated IS NOT NULL AND project.updated != '0000-00-00')
                                        "
                        ),
                        array(
                            'label' => 'Proyectos publicados',
                            'sql'   => "SELECT COUNT(project.id)
                                        FROM project
                                        WHERE (project.published IS NOT NULL AND project.published != '0000-00-00')
                                        AND project.status > 0
                                        "
                        ),
                        array(
                            'label' => 'Proyectos exitosos',
                            'sql'   => "SELECT COUNT(project.id)
                                        FROM project
                                        WHERE (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                                        AND project.status > 0
                                        "
                        ),
                        array(
                            'label' => 'Proyectos archivados',
                            'sql'   => "SELECT COUNT(project.id)
                                        FROM project
                                        WHERE (project.closed IS NOT NULL AND project.closed != '0000-00-00')
                                        AND project.status > 0
                                        "
                        ),
                        array(
                            'label' => 'Porcentaje proyectos exitosos',
                            'sql'   => "SELECT  (
                                                SELECT COUNT(project.id)
                                                FROM project
                                                WHERE (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                                                AND project.status > 0
                                            ) /
                                                (
                                                SELECT COUNT(project.id)
                                                FROM project
                                                WHERE (
                                                    (project.passed IS NOT NULL AND project.passed != '0000-00-00')
                                                    OR (project.closed IS NOT NULL AND project.closed != '0000-00-00')
                                                    )
                                                AND project.status > 0
                                            ) * 100 as percent
                                        FROM DUAL
                                        ",
                            'unit'   => '%',
                            'format'   => '2dec'
                        )
                    )
                ),
                'categories' => array(
                    'label' => 'Datos por categorias',
                    'values' => array(
                        array(
                            'label' => 'Proyectos publicados segun categoria',
                            'sql'   => "SELECT
                                            CONCAT(category.name, ' (', COUNT(project_category.project) , ')')
                                        FROM project_category
                                        INNER JOIN category
                                            ON project_category.category = category.id
                                        INNER JOIN project
                                            ON project_category.project = project.id
                                            AND (project.published IS NOT NULL AND project.published != '0000-00-00')
                                        GROUP BY project_category.category
                                        ORDER BY COUNT(project_category.project) DESC
                                        ",
                            'result'=> 'rows'
                        )
                    )
                ),
                'rewards' => array(
                    'label' => 'Retornos y recompensas',
                    'values' => array(
                        array(
                            'label' => 'Cofinanciadores que renuncian a recompensa',
                            'sql'   => "SELECT COUNT(invest.id)
                                        FROM invest
                                        WHERE invest.resign = 1
                                        AND invest.status IN (0, 1, 3, 4)
                                        "
                        ),
                        array(
                            'label' => 'Recompensa elegida de menos de 15 euros',
                            'sql'   => "SELECT SUM(amourew)
                                        FROM (
                                            SELECT COUNT(invest.id) as amourew
                                            FROM invest
                                            LEFT JOIN invest_reward
                                                ON invest_reward.invest = invest.id
                                            LEFT JOIN reward
                                                ON reward.id = invest_reward.reward
                                            WHERE reward.id IS NOT NULL
                                            AND (invest.resign IS NULL OR invest.resign = 0)
                                            AND reward.amount < 15
                                            GROUP BY reward.id
                                        ) as temp
                                        "
                        ),
                        array(
                            'label' => 'Recompensa elegida de 15 a 30 euros',
                            'sql'   => "SELECT SUM(amourew)
                                        FROM (
                                            SELECT COUNT(invest.id) as amourew
                                            FROM invest
                                            LEFT JOIN invest_reward
                                                ON invest_reward.invest = invest.id
                                            LEFT JOIN reward
                                                ON reward.id = invest_reward.reward
                                            WHERE reward.id IS NOT NULL
                                            AND (invest.resign IS NULL OR invest.resign = 0)
                                            AND reward.amount >= 15 AND reward.amount <= 30
                                            GROUP BY reward.id
                                        ) as temp
                                        "
                        ),
                        array(
                            'label' => 'Recompensa elegida de 30 a 100 euros',
                            'sql'   => "SELECT SUM(amourew)
                                        FROM (
                                            SELECT COUNT(invest.id) as amourew
                                            FROM invest
                                            LEFT JOIN invest_reward
                                                ON invest_reward.invest = invest.id
                                            LEFT JOIN reward
                                                ON reward.id = invest_reward.reward
                                            WHERE reward.id IS NOT NULL
                                            AND (invest.resign IS NULL OR invest.resign = 0)
                                            AND reward.amount >= 30 AND reward.amount <= 100
                                            GROUP BY reward.id
                                        ) as temp
                                        "
                        ),
                        array(
                            'label' => 'Recompensa elegida de 100 a 400 euros',
                            'sql'   => "SELECT SUM(amourew)
                                        FROM (
                                            SELECT COUNT(invest.id) as amourew
                                            FROM invest
                                            LEFT JOIN invest_reward
                                                ON invest_reward.invest = invest.id
                                            LEFT JOIN reward
                                                ON reward.id = invest_reward.reward
                                            WHERE reward.id IS NOT NULL
                                            AND (invest.resign IS NULL OR invest.resign = 0)
                                            AND reward.amount >= 100 AND reward.amount <= 400
                                            GROUP BY reward.id
                                        ) as temp
                                        "
                        ),
                        array(
                            'label' => 'Recompensa elegida de mas de 400 euros',
                            'sql'   => "SELECT SUM(amourew)
                                        FROM (
                                            SELECT COUNT(invest.id) as amourew
                                            FROM invest
                                            LEFT JOIN invest_reward
                                                ON invest_reward.invest = invest.id
                                            LEFT JOIN reward
                                                ON reward.id = invest_reward.reward
                                            WHERE reward.id IS NOT NULL
                                            AND (invest.resign IS NULL OR invest.resign = 0)
                                            AND reward.amount >= 400
                                            GROUP BY reward.id
                                        ) as temp
                                        "
                        ),
                        array(
                            'label' => 'Tipo de recompensa más utilizada en proyectos exitosos',
                            'sql'   => "SELECT reward.icon, COUNT(reward.project) as uses
                                        FROM reward
                                        INNER JOIN project
                                            ON  project.id = reward.project
                                            AND project.status IN (4, 5)
                                        WHERE reward.type = 'individual'
                                        GROUP BY reward.icon
                                        ORDER BY uses DESC
                                        LIMIT 1
                                        "
                        ),
                        array(
                            'label' => 'Licencia más utilizada en retornos de proyectos exitosos',
                            'sql'   => "SELECT reward.license, COUNT(reward.project) as uses
                                        FROM reward
                                        INNER JOIN project
                                            ON  project.id = reward.project
                                            AND project.status IN (4, 5)
                                        WHERE reward.type = 'social'
                                        GROUP BY reward.license
                                        ORDER BY uses DESC
                                        LIMIT 1
                                        "
                        )
                    )
                )
                /*
                 *
                ,
                'report5' => array(
                    'label' => 'Informe tal 5',
                    'values' => array(
                        array(
                            'label' => 'A',
                            'sql'   => "SELECT CONCAT('Aa') FROM DUAL"
                        ),
                        array(
                            'label' => 'B',
                            'sql'   => "SELECT CONCAT('Bb') FROM DUAL"
                        ),
                        array(
                            'label' => '',
                            'sql'   => ""
                        )
                    )
                )
                 * 
                 */
            );

        public static function getList() {

            $list = array();

            foreach (static::$reports as $repId=>$rep) {
                $list[$repId] = $rep['label'];
            }

            return $list;

        }

        public static function getReport($req, $filters = array()) {

                $report = array();

                if (!in_array($req, array_keys(static::$reports))) {
                    return null;
                }

                $report['columns'] = array('Total');


                // segun el informe cogemos una serie de datos
                foreach (static::$reports[$req]['values'] as $key=>$fConf) {
                    $report['rows'][$key] = $fConf['label'];
                    // aqui si hay varias columnas, get data nos dara varios datos (tema fechas)

                    // resultados especiales de varios datos
                    if ($fConf['result'] == 'rows') {
                        $report['data'][$key] = self::getDataRow($fConf['sql'], $filters, $fConf['unit'], $fConf['format']);
                    } else {
                        $report['data'][$key] = self::getData($fConf['sql'], $filters, $fConf['unit'], $fConf['format']);
                    }
                }

                return $report;
        }

        /*
         * Obtiene un dato del sql recibido
         * //@TODO : aplicar filtros
         * // segun filtros y otro parametro sabremos si hay que sacar un array de varias columnas de dtos
         */
        private static function getData($sql, $filters, $unit = '', $format = null) {

                if (empty($sql)) return array();

                $query = Model::query($sql);
                $data = $query->fetchColumn();
                switch ($format) {
                    case '2dec':
                        $data = \number_format($data, 2, ',', '');
                        break;
                    case 'amount':
                        $data = \number_format($data, 0, '', '.');
                        break;
                    case 'amount2dec':
                        $data = \number_format($data, 2, ',', '.');
                        break;
                }

                if ($unit != '') {
                    $data .= ' '.$unit;
                }

                return array($data);
        }

        /*
         * PAra datos de varios valores
         */
        private static function getDataRow($sql, $filters) {

                if (empty($sql)) return array();

                $query = Model::query($sql);
                $rows = $query->fetchAll(\PDO::FETCH_NUM);
                $data = '';
                foreach ($rows as $row) {
                    if ($data == '') {
                        $data .= $row[0];
                    } else {
                        $data .= '<br /> '. $row[0];
                    }
                }

                return array($data);
        }

	}
}