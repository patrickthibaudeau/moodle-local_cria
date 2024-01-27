<?php
require_once('../../../config.php');
use local_cria\questions;

$intent_id = required_param('intent_id', PARAM_INT);

$QUESTION = new questions($intent_id);

$QUESTION->export_excel();