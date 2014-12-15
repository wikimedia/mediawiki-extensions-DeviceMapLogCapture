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
		global $wgDeviceMapDatabase, $wgDeviceMapLog;
		$retval = true;
		if ( $wgDeviceMapDatabase ) {
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

		if ( $wgDeviceMapLog ) {
			$msg = implode( "\t", array(
				wfTimestampNow(),
				// Replace tabs with spaces in all strings
				str_replace( "\t", ' ', $token ),
				str_replace( "\t", ' ', $site ),
				(int)$eventId,
				str_replace( "\t", ' ', $deviceMap ),
				str_replace( "\t", ' ', $countryCode ),
				str_replace( "\t", ' ', $userAgent ),
			) );
			MWLoggerLegacyLogger::emit( $msg, $wgDeviceMapLog );
		}
		return $retval;
	}
}
