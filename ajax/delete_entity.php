<?php
use local_cria\entity;

require_once('../../config.php');
$context = context_system::instance();
require_login(1, false);

$entity_id = required_param('id', PARAM_INT);

//Create entity object
$ENTITY = new entity($entity_id);

//Delete entity
$ENTITY->delete_record();