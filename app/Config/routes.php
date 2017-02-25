<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/web/error', array('controller' => 'web', 'plugin' => 'web', 'action' => 'error'));
	Router::connect('/web/administrador/nacional', array('controller' => 'montoNacional', 'plugin' => 'web', 'action' => 'index'));
	Router::connect('/web/administrador/nacional/:action', array('controller' => 'montoNacional', 'plugin' => 'web'));
	Router::connect('/web/administrador/nacional/eliminar/*', array('controller' => 'montoNacional', 'plugin' => 'web', 'action' => 'eliminar'));
	Router::connect('/web/reportes/solicitudes', array('controller' => 'reports', 'plugin' => 'web', 'action' => 'requestReport'));
	Router::connect('/web/reportes/actividades', array('controller' => 'reports', 'plugin' => 'web', 'action' => 'eventsReport'));
	Router::connect('/web/reportes/saldos_disponibles', array('controller' => 'reports', 'plugin' => 'web', 'action' => 'availableBalancesReport'));
	Router::connect('/web/reportes/estado_de_cuenta', array('controller' => 'reports', 'plugin' => 'web', 'action' => 'movementsReport'));
	Router::connect('/web/reportes/nacional', array('controller' => 'reports', 'plugin' => 'web', 'action' => 'nationalReport'));
	
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
