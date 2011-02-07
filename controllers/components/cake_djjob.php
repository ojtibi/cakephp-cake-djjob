<?php
App::import('Lib', 'CakeDjjob.cake_job', array(
    'file' => 'jobs' . DS . 'cake_job.php',
));
App::import('Vendor', 'Djjob.DJJob', array(
    'file' => 'DJJob.php',
));

class CakeDjjobComponent extends Object {

    var $settings = array(
        'connection'=> 'default',
        'type'      => 'mysql',
    );

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object  A reference to the controller
 * @return void
 * @access public
 * @link http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components
 */
    function initialize(&$controller, $settings = array()) {
        $this->settings = array_merge($this->settings, $settings);
        $connection = ConnectionManager::getDataSource($this->settings['connection']);

        if ($this->settings['type'] == 'mysql') {
            DJJob::configure(
                implode(';', array(
                    "{$this->settings['type']}:host={$connection->config['host']}",
                    "dbname={$connection->config['database']}",
                    "port={$connection->config['port']}",
                )),
                $connection->config['login'],
                $connection->config['password']
            );
        } else {
            DJJob::configure(
                implode(';', array(
                    "{$this->settings['type']}:host={$connection->config['host']}",
                    "dbname={$connection->config['database']}",
                    "port={$connection->config['port']}",
                    "user={$connection->config['login']}",
                    "password={$connection->config['password']}"
                ))
            );
        }
    }

/**
 * Enqueues Jobs using DJJob
 *
 * Note that all Jobs enqueued using this system must extend the base CakeJob
 * class which is included in this plugin
 *
 * @param Job $job
 * @param string $queue
 * @param string $run_at
 * @return boolean True if enqueue is successful, false on failure
 */
    function enqueue(CakeJob $job, $queue = "default", $run_at = null) {
        return DJJob::enqueue($job, $queue, $run_at);
    }

/**
 * Bulk Enqueues Jobs using DJJob
 *
 * @param array $jobs
 * @param string $queue
 * @param string $run_at
 * @return boolean True if bulk enqueue is successful, false on failure
 */
    function bulkEnqueue($jobs, $queue = "default", $run_at = null) {
        return DJJob::bulkEnqueue($jobs, $queue, $run_at);
    }

}