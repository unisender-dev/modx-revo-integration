<?php
/**
 * Build the setup options form.
 *
 * @package unisender
 * @subpackage build
 */
/* set some default values */

/* get values based on mode */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
	    $query = $modx->newQuery('modTemplateVar');
	    $query->where("name='unisender_list_ids' OR name='unisender_send_date' OR name='unisender_send_mode' OR name='unisender_template_chunk'");
	    $query->command('DELETE');
	    $query->limit(100);
	    $query->prepare();
	    $query->stmt->execute();
        break;
    case xPDOTransport::ACTION_UPGRADE:
        break;
    case xPDOTransport::ACTION_UNINSTALL:
	    $query = $modx->newQuery('modTemplateVar');
	    $query->where("name='unisender_list_ids' OR name='unisender_send_date' OR name='unisender_send_mode' OR name='unisender_template_chunk'");
	    $query->command('DELETE');
		$query->limit(100);
	    $query->prepare();
	    $query->stmt->execute();
        break;
}

return $output;