<?php

class DeviceMapLogCaptureHooks {

	/* Static Methods */

	/**
	 * LoadExtensionSchemaUpdates hook
	 * @return Boolean: always true
	 */
	public static function loadExtensionSchemaUpdates( $updater = null ) {
		$dir = dirname( __FILE__ ) . '/';

		if ( $updater === null ) {
			global $wgExtNewTables, $wgExtNewIndexes, $wgExtNewFields;
			$wgExtNewTables[] = array( 'device_map_log_capture', $dir . 'patches/DeviceMapLogCapture.sql' );
		} else {
			$updater->addExtensionUpdate( array( 'addTable', 'device_map_log_capture',
				$dir . 'patches/DeviceMapLogCapture.sql', true ) );
		}
		return true;
	}

	public static function recordDevice( $eventId, $token, $site, $deviceMap, $countryCode, $userAgent ) {

	$retval = true;
	$dbw = wfGetDB( DB_MASTER );
	$dbw->begin();
	$data = array(
		'action_time' => $dbw->timestamp(),
		'session_id' => (string) $token,
		'site' => (string) $site,
		'event_id' => (int) $eventId,
		'dmap' => (string) $deviceMap,
		'country_code' => (string) $countryCode,
		'user_agent' => (string) $userAgent,
	);
	$db_status = $dbw->insert( 'device_map_log_capture', $data, __METHOD__ );
	$dbw->commit();
	}
}
