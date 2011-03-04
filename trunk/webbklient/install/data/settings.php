<?php

global $install_config;
$install_config = array();

/*
 * sql-file for database-install of webclient
 */
$install_config['sql_file'] = 'oop_tables_and_inital_data.sql';


/*
 * File with a template of the CI config-file database.php
 * Triggers in the file that will be replaced:
 * {DB_HOSTNAME}     - required
 * {DB_DATABASENAME} - required
 * {DB_USERNAME}     - required
 * {DB_PASSWORD}     - required
 * {DB_TYPE}         - required
 */
$install_config['ci_database_template'] = 'ci_default_database.php.txt';


?>