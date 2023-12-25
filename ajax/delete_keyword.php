<?php
use local_cria\keyword;

require_once('../../config.php');
$context = context_system::instance();
require_login(1, false);

$keyword_id = required_param('id', PARAM_INT);

//Create entity object
$KEYWORD = new keyword($keyword_id);

//Delete entity
$KEYWORD->delete_record();